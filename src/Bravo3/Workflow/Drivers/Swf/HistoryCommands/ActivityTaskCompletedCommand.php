<?php
namespace Bravo3\Workflow\Drivers\Swf\HistoryCommands;

use Bravo3\Workflow\Enum\HistoryItemState;
use Bravo3\Workflow\Workflow\WorkflowHistory;
use Bravo3\Workflow\Workflow\WorkflowHistoryItem;

class ActivityTaskCompletedCommand extends AbstractHistoryCommand
{
    public function apply(WorkflowHistory $history)
    {
        $item = $this->getHistoryItem($history, $this->getAttribute('scheduledEventId'));
        $item->setTimeEnded($this->timestamp);
        $item->setResult($this->getAttribute('result'));
        $item->setState(HistoryItemState::COMPLETED());
        $history->add($item);
    }
}
