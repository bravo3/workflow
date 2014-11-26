<?php
namespace Bravo3\Workflow\Drivers\Swf;

use Bravo3\Workflow\Drivers\DecisionEngineInterface;
use Bravo3\Workflow\Drivers\Swf\HistoryCommands\HistoryCommandInterface;
use Bravo3\Workflow\Drivers\Swf\HistoryCommands\WorkflowExecutionStartedCommand;
use Bravo3\Workflow\Events\DecisionEvent;
use Bravo3\Workflow\Workflow\WorkflowHistory;
use Guzzle\Service\Resource\Model;
use Symfony\Component\Yaml\Yaml;

class SwfDecisionEngine extends SwfEngine implements DecisionEngineInterface
{
    /**
     * @var array
     */
    protected $command_map;

    public function __construct(array $aws_config, array $swf_config)
    {
        parent::__construct($aws_config, $swf_config);
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
                'domain'   => $this->getConfig('domain', null, true),
                'taskList' => [
                    'name' => $this->getConfig('tasklist', null, true),
                ],
                'identity' => $this->getConfig('identity', static::DEFAULT_IDENTITY, false),
            ];

            if ($token) {
                $args['nextPageToken'] = $token;
            }

            // Query SWF for task/more history
            $model = $this->swf->pollForDecisionTask($args);

            if ($event) {
                $this->addHistory($event, $model);
            } else {
                $event = new DecisionEvent();
                $this->hydrateWorkflowEvent($event, $model);
                $this->addHistory($event, $model);
            }

        } while ($token = $model->get('nextPageToken'));
    }

    /**
     * Add history from a Guzzle object to the workflow
     *
     * @param DecisionEvent $event
     * @param Model         $model
     */
    protected function addHistory(DecisionEvent $event, Model $model)
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
     * @param array $history_item
     */
    protected function parseHistoryItem(WorkflowHistory $history, array $history_item)
    {
        if (array_key_exists($history_item['eventType'], $this->command_map)) {
            $class = $this->command_map[$history_item['eventType']]['class'];

            /** @var HistoryCommandInterface $cmd */
            $cmd = new $class(
                new \DateTime($history_item['eventTimestamp']),
                $history_item[$this->command_map[$history_item['eventType']]['args']],
                $history_item['eventId']
            );

            $cmd->apply($history);
        }
    }
}
