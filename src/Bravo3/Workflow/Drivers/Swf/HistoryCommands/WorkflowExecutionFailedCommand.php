<?php
namespace Bravo3\Workflow\Drivers\Swf\HistoryCommands;

use Bravo3\Workflow\Workflow\WorkflowHistory;

class WorkflowExecutionFailedCommand extends AbstractHistoryCommand
{
    public function apply(WorkflowHistory $history)
    {
        $reason = $this->getAttribute('reason');
        if ($details = $this->getAttribute('details', null)) {
            $reason .= ' ('.$details.')';
        }

        $history->setTimeEnded($this->timestamp);
        $history->failWorkflow('Failed: '.$reason);
    }
}
