<?php
namespace Bravo3\Workflow\Drivers\Swf\HistoryCommands;

use Bravo3\Workflow\Workflow\WorkflowHistory;

class WorkflowExecutionTerminatedCommand extends AbstractHistoryCommand
{
    public function apply(WorkflowHistory $history)
    {
        $reason = $this->getAttribute('reason');

        if ($details = $this->getAttribute('details', null)) {
            $reason .= ' ('.$details.')';
        }

        if ($cause = $this->getAttribute('cause', null)) {
            $reason .= ' (Cause: '.$cause.')';
        }

        $history->setTimeEnded($this->timestamp);
        $history->setWorkflowFailed('Terminated: '.$reason);
    }
}
