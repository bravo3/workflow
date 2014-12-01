<?php
namespace Bravo3\Workflow\Task;

use Bravo3\Workflow\Memory\MemoryPoolInterface;

interface TaskInterface
{
    public function __construct(MemoryPoolInterface $memory_pool, array $input);

    /**
     * Code to be executed by the WORKER when the task is run
     *
     * @return void
     */
    public function execute();

    /**
     * Code to be executed by the DECIDER before scheduling the task
     *
     * @return void
     */
    public function pre();

    /**
     * Code executed by the DECIDER on workflow success
     *
     * @return void
     */
    public function succeed();

    /**
     * Code executed by the DECIDER on workflow failure
     *
     * @return void
     */
    public function failed();

    /**
     * Code executed by the DECIDER when a workflow task completes, regardless of success
     *
     * @return void
     */
    public function done();
}
