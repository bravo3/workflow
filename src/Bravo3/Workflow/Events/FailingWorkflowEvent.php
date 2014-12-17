<?php
namespace Bravo3\Workflow\Events;

use Bravo3\Workflow\Workflow\WorkflowInterface;

class FailingWorkflowEvent extends WorkflowAwareEvent
{
    /**
     * @var string
     */
    protected $reason;

    public function __construct(WorkflowInterface $workflow, $execution_id, $reason = null)
    {
        parent::__construct($workflow, $execution_id);
        $this->reason = $reason;
    }

    /**
     * Get failure reason
     *
     * @return string
     */
    public function getReason()
    {
        return $this->reason;
    }
}
