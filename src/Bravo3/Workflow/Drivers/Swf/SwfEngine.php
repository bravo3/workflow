<?php
namespace Bravo3\Workflow\Drivers\Swf;

use Aws\Common\Aws;
use Aws\Swf\SwfClient;
use Bravo3\Workflow\Drivers\AbstractEngine;
use Bravo3\Workflow\Events\WorkflowEvent;
use Guzzle\Service\Resource\Model;

abstract class SwfEngine extends AbstractEngine
{
    /**
     * @var SwfClient
     */
    protected $swf;

    /**
     * Create an SWF engine
     *
     * $swf_config contains:
     *      'domain' => (string) SWF domain (required)
     *      'tasklist' => (string) SWF tasklist to pull a decision/work task from (required)
     *      'identity' => (string) Identity of the decider to pass back to SWF
     *
     * @param array $aws_config AWS connection parameters
     */
    public function __construct(array $aws_config)
    {
        parent::__construct();
        $aws       = Aws::factory($aws_config);
        $this->swf = $aws->get('swf');
    }

    /**
     * Hydrate common values from an Guzzle model to an event
     *
     * @param WorkflowEvent $event
     * @param Model         $model
     */
    protected function hydrateWorkflowEvent(WorkflowEvent $event, Model $model)
    {
        $event->setEventId($model->get('startedEventId'));
        $event->setExecutionId($model->get('workflowExecution')['workflowId']);
        $event->setRunId($model->get('workflowExecution')['runId']);
        $event->setToken($model->get('taskToken'));
        $event->setWorkflowName($model->get('workflowType')['name']);
        $event->setWorkflowVersion($model->get('workflowType')['version']);
    }
}
