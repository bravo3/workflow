<?php
namespace Bravo3\Workflow\Task;

use Bravo3\Workflow\Memory\MemoryPoolInterface;

interface TaskInterface
{
    public function __construct(MemoryPoolInterface $memory_pool, $input);

    /**
     * Code to be executed by the WORKER when the task is run
     *
     * @return void
     */
    public function execute();
}
