<?php
namespace Bravo3\Workflow\Task;

use Bravo3\Workflow\Memory\MemoryPoolInterface;

abstract class AbstractTask implements TaskInterface
{
    /**
     * @var array
     */
    protected $input;

    /**
     * @var MemoryPoolInterface
     */
    protected $memory_pool;

    public function __construct(MemoryPoolInterface $memory_pool, array $input)
    {
        $this->memory_pool = $memory_pool;
        $this->input       = $input;
    }

    /**
     * Code to be executed before scheduling the task
     *
     * @return void
     */
    public function pre()
    {
    }

    /**
     * Code executed on workflow success
     *
     * @return void
     */
    public function succeed()
    {
    }

    /**
     * Code executed on workflow failure
     *
     * @return void
     */
    public function failed()
    {
    }

    /**
     * Code executed when a workflow task completes, regardless of success
     *
     * @return void
     */
    public function done()
    {
    }
}
