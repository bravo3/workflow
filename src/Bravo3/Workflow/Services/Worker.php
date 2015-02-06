<?php
namespace Bravo3\Workflow\Services;

use Bravo3\Workflow\Enum\Event;
use Bravo3\Workflow\Events\WorkEvent;
use Bravo3\Workflow\Memory\JailedMemoryPool;
use Bravo3\Workflow\Task\TaskInterface;
use Bravo3\Workflow\Task\TaskSchema;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * A service that handles work tasks that require execution
 */
class Worker extends WorkflowService implements EventSubscriberInterface
{
    /**
     * Returns an array of event names this subscriber wants to listen to.
     *
     * The array keys are event names and the value can be:
     *
     *  * The method name to call (priority defaults to 0)
     *  * An array composed of the method name to call and the priority
     *  * An array of arrays composed of the method names to call and respective
     *    priorities, or 0 if unset
     *
     * For instance:
     *
     *  * array('eventName' => 'methodName')
     *  * array('eventName' => array('methodName', $priority))
     *  * array('eventName' => array(array('methodName1', $priority), array('methodName2'))
     *
     * @return array The event names to listen to
     *
     * @api
     */
    public static function getSubscribedEvents()
    {
        return [Event::TASK_WORK_READY => 'processWorkEvent'];
    }

    public function processWorkEvent(WorkEvent $event)
    {
        foreach ($this->getWorkflow()->getTasks() as $task) {
            if ($event->getActivityName() == $task->getActivityName() &&
                $event->getActivityVersion() == $task->getActivityVersion()
            ) {
                $this->executeTask($task, $event);
            }
        }
    }

    /**
     * Execute a task
     *
     * @param TaskSchema $task
     * @param WorkEvent  $event
     */
    protected function executeTask(TaskSchema $task, WorkEvent $event)
    {
        $memory_pool = $this->getWorkflow()->getJailMemoryPool() ?
            JailedMemoryPool::jail($this->getMemoryPool(), ':'.$event->getExecutionId()) :
            $this->getMemoryPool();

        $class = $task->getClass();

        /** @var TaskInterface $obj */
        $obj = new $class($memory_pool, $event->getInput(), $this->getAuxPayload());

        if (!($obj instanceof TaskInterface)) {
            throw new \DomainException("Class for task ".$task->getActivityName()." is not a TaskInterface");
        }

        $obj->execute($event);
    }
}
