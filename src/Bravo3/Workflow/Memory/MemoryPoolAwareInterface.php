<?php
namespace Bravo3\Workflow\Memory;

interface MemoryPoolAwareInterface
{
    /**
     * Get the memory pool
     *
     * @return MemoryPoolInterface
     */
    public function getMemoryPool();

    /**
     * Set the memory pool
     *
     * @param MemoryPoolInterface $memory_pool
     * @return void
     */
    public function setMemoryPool(MemoryPoolInterface $memory_pool);
}
