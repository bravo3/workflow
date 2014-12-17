<?php
namespace Bravo3\Workflow\Tests\Resources\Tasks;

use Bravo3\Workflow\Events\WorkEvent;
use Bravo3\Workflow\Exceptions\TaskFailedException;
use Bravo3\Workflow\Task\AbstractTask;

class CharlieTask extends AbstractTask
{
    const EXPECTED_INPUT = "bravo's your man";

    /**
     * Code to be executed by the WORKER when the task is run
     *
     * @param WorkEvent $event
     * @return void
     */
    public function execute(WorkEvent $event)
    {
        if ($event->getInput() !== self::EXPECTED_INPUT) {
            throw new TaskFailedException(
                "Input is incorrect. Should be: '".self::EXPECTED_INPUT."', is '".$event->getInput()."'", $event
            );
        }

        $this->memory_pool->set('charlie', 3);
        $this->memory_pool->set('state', 'COMPLETE');
        $event->setResult("charlie wins!");
    }
}