<?php
namespace Bravo3\Workflow\Services;

use Bravo3\Workflow\Enum\HistoryItemState;
use Bravo3\Workflow\Task\TaskSchema;
use Bravo3\Workflow\Workflow\WorkflowHistory;
use Bravo3\Workflow\Workflow\WorkflowHistoryItem;

class HistoryInspector
{
    /**
     * @var WorkflowHistory
     */
    protected $history;

    /**
     * @var array
     */
    protected $completion_cache = [];

    public function __construct(WorkflowHistory $history)
    {
        $this->history = $history;
    }

    /**
     * Check for incomplete activities
     *
     * @return bool
     */
    public function haveOpenActivities()
    {
        foreach ($this->history as $history_item) {
            /** @var WorkflowHistoryItem $history_item */
            if ($history_item->getState() == HistoryItemState::SCHEDULED() ||
                $history_item->getState() == HistoryItemState::RUNNING()
            ) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if a task has ever been scheduled based on activity name and version
     *
     * @param TaskSchema $task
     * @return bool
     */
    public function hasTaskBeenScheduled(TaskSchema $task)
    {
        foreach ($this->history as $history_item) {
            /** @var WorkflowHistoryItem $history_item */
            if ($task->getActivityName() == $history_item->getActivityName() &&
                $task->getActivityVersion() == $history_item->getActivityVersion()
            ) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get the count of a task in the history in a given state
     *
     * @param TaskSchema       $task
     * @param HistoryItemState $state
     * @return int
     */
    public function countTask(TaskSchema $task, HistoryItemState $state)
    {
        $cache_key = 'T:'.$task->getActivityName().':'.$task->getActivityVersion().':'.$state->key();

        if (array_key_exists($cache_key, $this->completion_cache)) {
            return $this->completion_cache[$cache_key];
        }

        $count = 0;
        foreach ($this->history as $history_item) {
            /** @var WorkflowHistoryItem $history_item */
            if ($task->getActivityName() == $history_item->getActivityName() &&
                $task->getActivityVersion() == $history_item->getActivityVersion() &&
                $history_item->getState() == $state
            ) {
                $count++;
            }
        }

        $this->completion_cache[$cache_key] = $count;
        return $count;
    }

    /**
     * Get the count of activities with a result in a given state
     *
     * @param string           $control
     * @param HistoryItemState $state
     * @return int
     */
    public function countControl($control, HistoryItemState $state)
    {
        $cache_key = 'C:'.$control.':'.$state->key();

        if (array_key_exists($cache_key, $this->completion_cache)) {
            return $this->completion_cache[$cache_key];
        }

        $count = 0;
        foreach ($this->history as $history_item) {
            /** @var WorkflowHistoryItem $history_item */
            if ($history_item->getControl() == $control && $history_item->getState() == $state) {
                $count++;
            }
        }

        $this->completion_cache[$cache_key] = $count;
        return $count;
    }
}
