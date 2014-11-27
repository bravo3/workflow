<?php
namespace Bravo3\Workflow\Drivers\Swf;

use Aws\Common\Aws;
use Aws\Swf\SwfClient;
use Bravo3\Workflow\Drivers\AbstractEngine;
use Bravo3\Workflow\Events\WorkflowEvent;
use Bravo3\Workflow\Exceptions\InsufficientDataException;
use Guzzle\Service\Resource\Model;

abstract class SwfEngine extends AbstractEngine
{
    const DEFAULT_IDENTITY = 'Bravo3 Workflow Engine';

    /**
     * @var SwfClient
     */
    protected $swf;

    /**
     * @var array
     */
    protected $swf_config;


    /**
     * Create an SWF engine
     *
     * $swf_config contains:
     *      'domain' => (string) SWF domain (required)
     *      'tasklist' => (string) SWF tasklist to pull a decision/work task from (required)
     *      'identity' => (string) Identity of the decider to pass back to SWF
     *
     * @param array $aws_config AWS connection parameters
     * @param array $swf_config SWF parameters
     */
    public function __construct(array $aws_config, array $swf_config)
    {
        parent::__construct();
        $aws              = Aws::factory($aws_config);
        $this->swf        = $aws->get('swf');
        $this->swf_config = $swf_config;
    }

    /**
     * Get an SWF parameter
     *
     * @param string $key
     * @param mixed  $default
     * @param bool   $mandatory
     * @return mixed
     */
    protected function getConfig($key, $default = null, $mandatory = false)
    {
        if (!array_key_exists($key, $this->swf_config)) {
            if ($mandatory) {
                throw new InsufficientDataException("Required SWF parameter '".$key."' is missing");
            }

            return $default;
        }

        return $this->swf_config[$key];
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
        $event->setWorkflowVersion($model->get('workflowExecution')['workflowId']);
    }
}
