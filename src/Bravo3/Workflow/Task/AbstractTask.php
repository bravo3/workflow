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

    /**
     * @var mixed
     */
    protected $aux_payload;

    public function __construct(MemoryPoolInterface $memory_pool, $input, $aux_payload = null)
    {
        $this->memory_pool = $memory_pool;
        $this->input       = $input;
        $this->aux_payload = $aux_payload;
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
            if ($task->getActivityName() == $activity_name) {
                if ($activity_version !== null) {
                    if ($task->getActivityVersion() == $activity_version) {
                        return clone $task;
                    }
                } else {
                    return clone $task;
                }
            }
        }

        throw new NotFoundException("Task '".$activity_name."' not found");
    }

    /**
     * Get AuxPayload
     *
     * @return mixed
     */
    protected function getAuxPayload()
    {
        return $this->aux_payload;
    }
}
