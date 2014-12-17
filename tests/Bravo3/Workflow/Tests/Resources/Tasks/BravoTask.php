<?php
namespace Bravo3\Workflow\Tests\Resources\Tasks;

use Bravo3\Workflow\Events\WorkEvent;
use Bravo3\Workflow\Task\AbstractTask;

class BravoTask extends AbstractTask
{
    /**
     * Code to be executed by the WORKER when the task is run
     *
     * @param WorkEvent $event
     * @return void
     */
    public function execute(WorkEvent $event)
    {
        $this->memory_pool->set('bravo', 2);
        $this->memory_pool->set('state', 'WORKING');
        $event->setResult("bravo's your man");
    }
}