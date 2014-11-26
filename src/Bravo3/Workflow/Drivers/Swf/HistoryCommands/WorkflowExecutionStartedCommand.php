<?php
namespace Bravo3\Workflow\Drivers\Swf\HistoryCommands;

use Bravo3\Workflow\Workflow\WorkflowHistory;

class WorkflowExecutionStartedCommand extends AbstractHistoryCommand
{
    public function apply(WorkflowHistory $history)
    {
        $history->setInput($this->getAttribute('input'));
        $history->setTimeStarted($this->timestamp);
    }
}
