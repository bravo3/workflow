<?php
namespace Bravo3\Workflow\Task;

use Bravo3\Workflow\Memory\MemoryPoolInterface;

abstract class AbstractTask implements TaskInterface
{
    /**
     * @var string
     */
    protected $input;

    /**
     * @var MemoryPoolInterface
     */
    protected $memory_pool;

    public function __construct(MemoryPoolInterface $memory_pool, $input)
    {
        $this->memory_pool = $memory_pool;
        $this->input       = $input;
    }
}
