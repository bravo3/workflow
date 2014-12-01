<?php
namespace Bravo3\Workflow\Tests\Resources\Tasks;

use Bravo3\Workflow\Task\AbstractTask;
use Bravo3\Workflow\Worker\WorkerParameterInterface;

class AlphaTask extends AbstractTask
{
    /**
     * Get the parameters that are required by the workflow controller to create a new task
     *
     * @return WorkerParameterInterface
     */
    public function getWorkerParameters()
    {

    }
}