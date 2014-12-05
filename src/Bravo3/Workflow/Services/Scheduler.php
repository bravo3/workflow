<?php
namespace Bravo3\Workflow\Services;

use Bravo3\Workflow\Enum\HistoryItemState;
use Bravo3\Workflow\Exceptions\UnexpectedValueException;
use Bravo3\Workflow\Memory\MemoryPoolInterface;
use Bravo3\Workflow\Task\TaskSchema;
use Bravo3\Workflow\Workflow\WorkflowHistory;

class Scheduler
{
    /**
     * @var WorkflowHistory
     */
    protected $history;

    /**
     * @var MemoryPoolInterface
     */
    protected $memory_pool;

    /**
     * @var HistoryInspector
     */
    protected $history_inspector;

    public function __construct(WorkflowHistory $history, MemoryPoolInterface $memory_pool)
    {
        $this->history           = $history;
        $this->memory_pool       = $memory_pool;

        $this->history_inspector = new HistoryInspector($history);
    }

    /**
     * Check if the given task meets its schedule requirements
     *
     * @param TaskSchema $task
     * @return bool
     */
    public function canScheduleTask(TaskSchema $task)
    {
        // Don't schedule again if this task has already been scheduled
        if ($this->history_inspector->hasTaskBeenScheduled($task)) {
            return false;
        }

        $requirements = $task->getRequires();

        if (!is_array($requirements) || count($requirements) == 0) {
            // No requirements, schedule away!
            return true;
        }

        // Check against every other type of requirement
        return $this->meetsActivityRequirements($this->getRequirementBlock($requirements, 'activity')) &&
               $this->meetsControlRequirements($this->getRequirementBlock($requirements, 'control')) &&
               $this->meetsVariableRequirements($this->getRequirementBlock($requirements, 'variable'));
    }

    /**
     * Check for incomplete activities
     *
     * Wrapper for the same function of the HistoryInspector
     *
     * @return bool
     */
    public function haveOpenActivities()
    {
        return $this->history_inspector->haveOpenActivities();
    }

    /**
     * Get a requirement block from the task schema, throwing an exception if it is incorrectly formatted
     *
     * @param array  $requirements
     * @param string $block
     * @return array
     */
    protected function getRequirementBlock(array $requirements, $block)
    {
        if (!isset($requirements[$block])) {
            return [];
        }

        $r = $requirements[$block];

        if (!is_array($r)) {
            throw new UnexpectedValueException("Task requirements '".$block."' must be an array");
        }

        return $r;
    }

    /**
     * Check if we meet all activity requirements
     *
     * @param array $requirements
     * @return bool
     */
    protected function meetsActivityRequirements(array $requirements)
    {
        foreach ($requirements as $activity => $count) {
            if ($this->history_inspector->countActivityName($activity, HistoryItemState::COMPLETED()) != $count) {
                return false;
            }
        }

        return true;
    }

    /**
     * Check if we meet all control requirements
     *
     * @param array $requirements
     * @return bool
     */
    protected function meetsControlRequirements(array $requirements)
    {
        foreach ($requirements as $control => $count) {
            if ($this->history_inspector->countControl($control, HistoryItemState::COMPLETED()) != $count) {
                return false;
            }
        }

        return true;
    }

    /**
     * Check if we meet all variable requirements
     *
     * @param array $requirements
     * @return bool
     */
    protected function meetsVariableRequirements(array $requirements)
    {
        foreach ($requirements as $key => $value) {
            if ($this->memory_pool->get($key) != $value) {
                return false;
            }
        }

        return true;
    }
}
