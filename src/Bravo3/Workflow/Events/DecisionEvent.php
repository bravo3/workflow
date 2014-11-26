<?php
namespace Bravo3\Workflow\Events;

use Bravo3\Workflow\Workflow\WorkflowHistory;

class DecisionEvent extends WorkflowEvent
{
    /**
     * @var WorkflowHistory
     */
    protected $history = null;

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
}
