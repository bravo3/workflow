<?php
namespace Bravo3\Workflow\Drivers\Swf;

use Bravo3\Workflow\Drivers\DecisionEngineInterface;
use Bravo3\Workflow\Drivers\Swf\HistoryCommands\HistoryCommandInterface;
use Bravo3\Workflow\Drivers\Swf\WorkflowCommands\WorkflowCommandInterface;
use Bravo3\Workflow\Enum\Event;
use Bravo3\Workflow\Enum\WorkflowResult;
use Bravo3\Workflow\Events\CompletingWorkflowEvent;
use Bravo3\Workflow\Events\DecisionEvent;
use Bravo3\Workflow\Events\FailingWorkflowEvent;
use Bravo3\Workflow\Events\SchedulingTaskEvent;
use Bravo3\Workflow\Exceptions\UnexpectedValueException;
use Bravo3\Workflow\Workflow\Decision;
use Bravo3\Workflow\Workflow\WorkflowHistory;
use Guzzle\Service\Resource\Model;
use Symfony\Component\Yaml\Yaml;

class SwfDecisionEngine extends SwfEngine implements DecisionEngineInterface
{
    /**
     * @var array
     */
    protected $command_map;

    public function __construct(array $aws_config)
    {
        parent::__construct($aws_config);
        $this->command_map = Yaml::parse(__DIR__.'/HistoryCommands/CommandMap.yml');
    }

    /**
     * Check for a decision task
     *
     * @return void
     */
    public function checkForTask()
    {
        /** @var DecisionEvent */
        $event = null;
        $token = null;
        do {
            // SWF params
            $args = [
                'domain'   => $this->getWorkflow()->getDomain(),
                'taskList' => [
                    'name' => $this->getWorkflow()->getTasklist(),
                ],
                'identity' => $this->getIdentity(),
            ];

            if ($token) {
                $args['nextPageToken'] = $token;
            }

            // Query SWF for task/more history
            $model = $this->swf->pollForDecisionTask($args);

            if ($model->get('startedEventId')) {
                if ($event) {
                    $this->addHistory($event, $model);
                } else {
                    $event = new DecisionEvent();
                    $this->hydrateWorkflowEvent($event, $model);
                    $this->addHistory($event, $model);
                }
            }

        } while ($token = $model->get('nextPageToken'));

        if ($event) {
            $context = $this->createEventContext($event);

            $context['workflow_name']    = $event->getWorkflowName();
            $context['workflow_version'] = $event->getWorkflowVersion();

            $this->logger->info(
                'Found decision task for "'.$event->getWorkflowName().'"',
                $context
            );

            // Dispatching an event here will let any number of deciders schedule tasks or modify the workflow reason
            $this->dispatch(Event::TASK_DECISION_READY, $event);

            // This will return the decision reason to SWF, while also firing notification events on each decision
            $this->processDecision($event->getDecision());
        }
    }

    /**
     * Process a workflow decision, sending the reason back to the workflow engine
     *
     * @param Decision $decision
     */
    public function processDecision(Decision $decision)
    {
        switch ($decision->getWorkflowResult()) {
            // Complete a workflow
            case WorkflowResult::COMPLETE():
                $class = 'RespondDecisionCompleteCommand';
                $event = new CompletingWorkflowEvent(
                    $this->getWorkflow(),
                    $decision->getExecutionId(),
                    $decision->getResult()
                );
                $this->dispatch(Event::DECISION_COMPLETE, $event);
                $this->getWorkflow()->onWorkflowSuccess($event);
                $this->getWorkflow()->onWorkflowComplete($event);
                break;

            // Fail a workflow
            case WorkflowResult::FAIL():
                $class = 'RespondDecisionFailedCommand';
                $event = new FailingWorkflowEvent(
                    $this->getWorkflow(),
                    $decision->getExecutionId(),
                    $decision->getReason()
                );
                $this->dispatch(Event::DECISION_FAIL, $event);
                $this->getWorkflow()->onWorkflowFailed($event);
                $this->getWorkflow()->onWorkflowComplete($event);
                break;

            // Send workflow commands
            case WorkflowResult::COMMAND():
                $class = 'RespondDecisionScheduleCommand';
                foreach ($decision->getScheduledTasks() as $task) {
                    $this->dispatch(Event::DECISION_SCHEDULE, new SchedulingTaskEvent($task));
                }
                break;

            // Unsupported response
            default:
                throw new UnexpectedValueException("Unknown workflow result: ".$decision->getWorkflowResult()->key());
        }

        $this->runCommand($class, ['decision' => $decision]);
    }

    /**
     * Create (and run) a workflow command
     *
     * @param string $class Workflow command short class name
     * @param array  $args  Command arguments
     * @param bool   $exec  Run the command after construction, if false the command will just be returned
     * @return WorkflowCommandInterface
     */
    private function runCommand($class, array $args, $exec = true)
    {
        $class = 'Bravo3\Workflow\Drivers\Swf\WorkflowCommands\\'.$class;

        /** @var WorkflowCommandInterface $cmd */
        $cmd = new $class($this->swf, $this->getWorkflow(), $args);

        if ($exec) {
            $cmd->execute();
        }

        return $cmd;
    }

    /**
     * Add history from a Guzzle object to the workflow
     *
     * @param DecisionEvent $event
     * @param Model         $model
     */
    private function addHistory(DecisionEvent $event, Model $model)
    {
        $history = $event->getHistory();
        $items   = $model->get('events');
        foreach ($items as $item) {
            $this->parseHistoryItem($history, $item);
        }
    }

    /**
     * Parse an SWF event
     *
     * @param WorkflowHistory $history
     * @param array           $history_item
     */
    private function parseHistoryItem(WorkflowHistory $history, array $history_item)
    {
        if (array_key_exists($history_item['eventType'], $this->command_map)) {
            $class = $this->command_map[$history_item['eventType']]['class'];

            $timestamp = new \DateTime();
            $timestamp->setTimestamp((int)$history_item['eventTimestamp']);

            /** @var HistoryCommandInterface $cmd */
            $cmd = new $class(
                $timestamp,
                $history_item[$this->command_map[$history_item['eventType']]['args']],
                $history_item['eventId']
            );

            $cmd->apply($history);
        }
    }
}
