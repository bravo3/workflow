<?php
namespace Bravo3\Workflow\Task;

use Bravo3\Workflow\Events\WorkEvent;
use Bravo3\Workflow\Memory\MemoryPoolInterface;
use Bravo3\Workflow\Workflow\Decision;
use Bravo3\Workflow\Workflow\WorkflowHistoryItem;
use Bravo3\Workflow\Workflow\WorkflowInterface;

interface TaskInterface
{
    public function __construct(MemoryPoolInterface $memory_pool, $input, $aux_payload = null);

    /**
     * Code to be executed by the WORKER when the task is run
     *
     * @param WorkEvent $event
     * @return void
     */
    public function execute(WorkEvent $event);

    /**
     * Code to be executed by the DECIDER when the task is complete
     *
     * This function allows your task to schedule additional tasks by manipulating the decision that follows the
     * success of this task.
     *
     * @param WorkflowInterface   $workflow
     * @param WorkflowHistoryItem $history_item
     * @param Decision            $decision
     * @return void
     */
    public function onSuccess(WorkflowInterface $workflow, WorkflowHistoryItem $history_item, Decision $decision);
}
