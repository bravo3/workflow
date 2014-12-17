<?php
namespace Bravo3\Workflow\Events;

use Bravo3\Workflow\Workflow\WorkflowInterface;
use Symfony\Component\EventDispatcher\Event;

class WorkflowAwareEvent extends Event
{
    /**
     * @var WorkflowInterface
     */
    protected $workflow;

    /**
     * @var string
     */
    protected $execution_id;

    public function __construct(WorkflowInterface $workflow, $execution_id)
    {
        $this->workflow     = $workflow;
        $this->execution_id = $execution_id;
    }

    /**
     * Get Workflow
     *
     * @return WorkflowInterface
     */
    public function getWorkflow()
    {
        return $this->workflow;
    }

    /**
     * Get workflow execution ID
     *
     * @return string
     */
    public function getExecutionId()
    {
        return $this->execution_id;
    }
}
