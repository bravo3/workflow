<?php
namespace Bravo3\Workflow\Drivers\Swf\HistoryCommands;

use Bravo3\Workflow\Enum\HistoryItemState;
use Bravo3\Workflow\Workflow\WorkflowHistory;
use Bravo3\Workflow\Workflow\WorkflowHistoryItem;

class ActivityTaskTimedOutCommand extends AbstractHistoryCommand
{
    public function apply(WorkflowHistory $history)
    {
        $reason = $this->getAttribute('timeoutType');
        if ($details = $this->getAttribute('details', null)) {
            $reason .= ' ('.$details.')';
        }

        $item = $this->getHistoryItem($history, $this->getAttribute('scheduledEventId'));
        $item->setTimeEnded($this->timestamp);
        $item->setState(HistoryItemState::TIMED_OUT());
        $item->setErrorMessage('(Timeout) '.$reason);
        $history->add($item);
    }
}
