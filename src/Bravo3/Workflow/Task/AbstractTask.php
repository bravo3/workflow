<?php
namespace Bravo3\Workflow\Task;

use Bravo3\Workflow\Exceptions\NotFoundException;
use Bravo3\Workflow\Memory\MemoryPoolInterface;
use Bravo3\Workflow\Workflow\Decision;
use Bravo3\Workflow\Workflow\WorkflowHistoryItem;
use Bravo3\Workflow\Workflow\WorkflowInterface;

abstract class AbstractTask implements TaskInterface
{
    /**
     * @var string
     */
    protected $input;

    /**
     * @var MemoryPoolInterface
     */
    protected $memory_pool;

    public function __construct(MemoryPoolInterface $memory_pool, $input)
    {
        $this->memory_pool = $memory_pool;
        $this->input       = $input;
    }

    /**
     * Code to be executed by the DECIDER when the task is complete
     *
     * This function allows your task to schedule additional tasks by manipulating the decision that follows the
     * success of this task.
     *
     * @param WorkflowInterface   $workflow
     * @param WorkflowHistoryItem $history_item
     * @param Decision            $decision
     */
    public function onSuccess(WorkflowInterface $workflow, WorkflowHistoryItem $history_item, Decision $decision)
    {
    }

    /**
     * Get a copy of a task from the workflow
     *
     * @param WorkflowInterface $workflow
     * @param string            $activity_name    The activity name we're looking for
     * @param string            $activity_version Optionally provide the activity version
     * @return TaskSchema
     */
    protected function getTask(WorkflowInterface $workflow, $activity_name, $activity_version = null)
    {
        foreach ($workflow->getTasks() as $task) {
            if ($task->getActivityName() == 'bravo') {
                return clone $task;
            }
        }

        throw new NotFoundException("Task '".$activity_name."' not found");
    }
}
