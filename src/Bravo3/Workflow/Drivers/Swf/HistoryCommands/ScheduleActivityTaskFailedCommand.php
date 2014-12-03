<?php
namespace Bravo3\Workflow\Drivers\Swf\HistoryCommands;

use Bravo3\Workflow\Enum\HistoryItemState;
use Bravo3\Workflow\Workflow\WorkflowHistory;
use Bravo3\Workflow\Workflow\WorkflowHistoryItem;

class ScheduleActivityTaskFailedCommand extends AbstractHistoryCommand
{
    public function apply(WorkflowHistory $history)
    {
        $activity = $this->getAttribute(['activityType', 'name']).'-'.$this->getAttribute(['activityType', 'version']);
        $cause    = $this->getAttribute('cause');

        $history->setActivityFailed();
        $history->setWorkflowFailed('Unable to schedule activity task: '.$activity.' ('.$cause.')');
    }
}
