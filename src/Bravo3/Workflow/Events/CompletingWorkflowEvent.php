<?php
namespace Bravo3\Workflow\Events;

use Bravo3\Workflow\Workflow\WorkflowInterface;

class CompletingWorkflowEvent extends WorkflowAwareEvent
{
    /**
     * @var string
     */
    protected $result;

    public function __construct(WorkflowInterface $workflow, $execution_id, $result = null)
    {
        parent::__construct($workflow, $execution_id);
        $this->result = $result;
    }

    /**
     * Get workflow result
     *
     * @return string
     */
    public function getResult()
    {
        return $this->result;
    }
}
