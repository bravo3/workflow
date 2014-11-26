<?php
namespace Bravo3\Workflow\Drivers\Swf\HistoryCommands;

use Bravo3\Workflow\Enum\HistoryItemState;
use Bravo3\Workflow\Workflow\WorkflowHistory;
use Bravo3\Workflow\Workflow\WorkflowHistoryItem;

class ActivityTaskScheduledCommand extends AbstractHistoryCommand
{
    public function apply(WorkflowHistory $history)
    {
        $item = new WorkflowHistoryItem();
        $item->setState(HistoryItemState::SCHEDULED());
        $item->setTimeScheduled($this->timestamp);
        $item->setEventId($this->event_id);
        $item->setInput($this->getAttribute('input'));
        $item->setControl($this->getAttribute('control'));
        $item->setActivityName($this->getAttribute(['activityType', 'name']));
        $item->setActivityVersion($this->getAttribute(['activityType', 'version']));

        $history->add($item);
    }
}
