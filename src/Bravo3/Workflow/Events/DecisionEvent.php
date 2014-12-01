<?php
namespace Bravo3\Workflow\Events;

use Bravo3\Workflow\Workflow\Decision;
use Bravo3\Workflow\Workflow\WorkflowHistory;

class DecisionEvent extends WorkflowEvent
{
    /**
     * @var WorkflowHistory
     */
    protected $history = null;

    /**
     * @var Decision
     */
    protected $decision = null;

    /**
     * Get the workflow history
     *
     * @return WorkflowHistory
     */
    public function getHistory()
    {
        if (!$this->history) {
            $this->history = new WorkflowHistory();
        }

        return $this->history;
    }

    /**
     * Get the resulting decision
     *
     * @return Decision
     */
    public function getDecision()
    {
        if (!$this->decision) {
            $this->decision = new Decision($this->getToken());
        }

        return $this->decision;
    }
}
