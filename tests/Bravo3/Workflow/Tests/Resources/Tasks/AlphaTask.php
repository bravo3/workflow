<?php
namespace Bravo3\Workflow\Tests\Resources\Tasks;

use Bravo3\Workflow\Events\WorkEvent;
use Bravo3\Workflow\Exceptions\TaskFailedException;
use Bravo3\Workflow\Task\AbstractTask;
use Bravo3\Workflow\Workflow\Decision;
use Bravo3\Workflow\Workflow\WorkflowHistoryItem;
use Bravo3\Workflow\Workflow\WorkflowInterface;

class AlphaTask extends AbstractTask
{
    /**
     * Code to be executed by the WORKER when the task is run
     *
     * @param WorkEvent $event
     * @return void
     */
    public function execute(WorkEvent $event)
    {
        if ($this->getAuxPayload() !== 'payload') {
            throw new TaskFailedException("Payload incorrect (".$this->getAuxPayload().")", $event);
        }

        $this->memory_pool->set('alpha', 1);
        $this->memory_pool->set('state', 'STARTING');
        $event->setResult('done woo!');
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
     * @return void
     */
    public function onSuccess(WorkflowInterface $workflow, WorkflowHistoryItem $history_item, Decision $decision)
    {
        if ($this->getAuxPayload() !== 'payload') {
            throw new \Exception("Payload incorrect (".$this->getAuxPayload().")");
        }

        $schema = $this->getTask($workflow, 'bravo');
        $schema->setInput('lalala');
        $decision->scheduledTask($schema);
    }
}
