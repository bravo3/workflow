<?php
namespace Bravo3\Workflow\Tests\Resources\Tasks;

use Bravo3\Workflow\Events\WorkEvent;
use Bravo3\Workflow\Exceptions\TaskFailedException;
use Bravo3\Workflow\Task\AbstractTask;

class BravoTask extends AbstractTask
{
    const EXPECTED_INPUT = 'STARTING';

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

        $event->setResult("bravo's your man");

        if ($event->getInput() == 'lalala') {
            $this->memory_pool->set('bravo', 2);
        } elseif ($event->getInput() !== self::EXPECTED_INPUT) {
            throw new TaskFailedException(
                "Input is incorrect. Should be: '".self::EXPECTED_INPUT."', is '".$event->getInput()."'", $event
            );
        }

        $this->memory_pool->set('state', 'WORKING');
    }
}