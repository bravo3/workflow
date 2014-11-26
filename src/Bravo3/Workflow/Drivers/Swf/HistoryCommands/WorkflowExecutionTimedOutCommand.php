<?php
namespace Bravo3\Workflow\Drivers\Swf\HistoryCommands;

use Bravo3\Workflow\Workflow\WorkflowHistory;

class WorkflowExecutionTimedOutCommand extends AbstractHistoryCommand
{
    public function apply(WorkflowHistory $history)
    {
        $history->setTimeEnded($this->timestamp);
        $history->failWorkflow('Timeout: '.$this->getAttribute('timeoutType'));
    }
}
