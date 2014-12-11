<?php
namespace Bravo3\Workflow\Tests\Resources\Tasks;

use Bravo3\Workflow\Task\AbstractTask;

class BravoTask extends AbstractTask
{
    /**
     * Code to be executed by the WORKER when the task is run
     *
     * @return void
     */
    public function execute()
    {
        $this->memory_pool->set('bravo', 2);
    }
}