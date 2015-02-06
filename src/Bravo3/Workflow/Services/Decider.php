<?php
namespace Bravo3\Workflow\Services;

use Bravo3\Workflow\Enum\Event;
use Bravo3\Workflow\Enum\HistoryItemState;
use Bravo3\Workflow\Enum\WorkflowResult;
use Bravo3\Workflow\Events\DecisionEvent;
use Bravo3\Workflow\Memory\JailedMemoryPool;
use Bravo3\Workflow\Memory\MemoryPoolInterface;
use Bravo3\Workflow\Task\TaskInterface;
use Bravo3\Workflow\Workflow\Decision;
use Bravo3\Workflow\Workflow\WorkflowHistory;
use Bravo3\Workflow\Workflow\WorkflowHistoryItem;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * A service that handles workflow decisions
 */
class Decider extends WorkflowService implements EventSubscriberInterface
{
    const MSG_WF_FAILED = 'Workflow failed';

    public static function getSubscribedEvents()
    {
        return [Event::TASK_DECISION_READY => 'processDecisionEvent'];
    }

    /**
     * @var bool
     */
    protected $fail_on_activity_failure = true;

    /**
     * Check if the entire workflow should fail if an activity fails
     *
     * @return boolean
     */
    public function getFailOnActivityFailure()
    {
        return $this->fail_on_activity_failure;
    }

    /**
     * Define if the entire workflow should fail if an activity fails
     *
     * @param boolean $fail_on_activity_failure
     * @return $this
     */
    public function setFailOnActivityFailure($fail_on_activity_failure)
    {
        $this->fail_on_activity_failure = $fail_on_activity_failure;
        return $this;
    }

    /**
     * Process the decision event, generating a decision response (included in the DecisionEvent)
     *
     * @param DecisionEvent $event
     */
    public function processDecisionEvent(DecisionEvent $event)
    {
        $history  = $event->getHistory();
        $decision = $event->getDecision();
        $tasks    = $this->getWorkflow()->getTasks();

        // Create a memory pool jailed to this execution
        $memory_pool = $this->getWorkflow()->getJailMemoryPool() ?
            JailedMemoryPool::jail($this->getMemoryPool(), ':'.$event->getExecutionId()) :
            $this->getMemoryPool();

        // Check if we need to execute any task events
        /** @var WorkflowHistoryItem $history_item */
        foreach ($history as $history_item) {
            $id = $history_item->getEventId();

            if ($history_item->getState() == HistoryItemState::COMPLETED()) {
                if ($memory_pool->get('history:'.$id.':completed') === null) {
                    $this->runTaskDecider($history_item, $decision, $memory_pool);
                    $memory_pool->set('history:'.$id.':completed', 1);
                }
            }
        }

        // Check if we need to fail
        if (($this->getFailOnActivityFailure() && $history->hasActivityFailure()) || $history->hasWorkflowFailed()) {
            $decision->setWorkflowResult(WorkflowResult::FAIL());
            $decision->setReason(implode(", ", $history->getErrorMessages()));
            return;
        }

        // Check if we need to schedule
        $parser    = new InputParser($history, $memory_pool);
        $scheduler = new Scheduler($history, $memory_pool);
        foreach ($tasks as $task) {
            if ($scheduler->canScheduleTask($task)) {
                $decision->scheduledTask($parser->compileTaskInput($task));
            }
        }

        // Check if we need to complete
        if (count($decision->getScheduledTasks()) == 0 && !$scheduler->haveOpenActivities()) {
            $decision->setWorkflowResult(WorkflowResult::COMPLETE());
        }
    }

    /**
     * Run the tasks success decider, allowing the task a chance to schedule some events
     *
     * @param WorkflowHistoryItem $history_item
     * @param Decision            $decision
     * @param MemoryPoolInterface $memory_pool
     */
    protected function runTaskDecider(
        WorkflowHistoryItem $history_item,
        Decision $decision,
        MemoryPoolInterface $memory_pool
    ) {
        foreach ($this->getWorkflow()->getTasks() as $task) {
            if ($history_item->getActivityName() == $task->getActivityName() &&
                $history_item->getActivityVersion() == $task->getActivityVersion()
            ) {
                $class = $task->getClass();

                /** @var TaskInterface $obj */
                $obj = new $class($memory_pool, $history_item->getInput());

                if (!($obj instanceof TaskInterface)) {
                    throw new \DomainException("Class for task ".$task->getActivityName()." is not a TaskInterface");
                }

                $obj->onSuccess($this->getWorkflow(), $history_item, $decision);
            }
        }
    }
}
