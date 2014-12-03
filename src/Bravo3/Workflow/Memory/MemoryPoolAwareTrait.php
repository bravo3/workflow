<?php
namespace Bravo3\Workflow\Memory;

trait MemoryPoolAwareTrait
{
    /**
     * @var MemoryPoolInterface
     */
    protected $memory_pool;

    /**
     * Get the memory pool
     *
     * @return MemoryPoolInterface
     */
    public function getMemoryPool()
    {
        return $this->memory_pool;
    }

    /**
     * Set the memory pool
     *
     * @param MemoryPoolInterface $memory_pool
     * @return void
     */
    public function setMemoryPool(MemoryPoolInterface $memory_pool)
    {
        $this->memory_pool = $memory_pool;
    }
}
