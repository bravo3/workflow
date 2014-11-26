<?php
namespace Bravo3\Workflow\Flags;

use Bravo3\Workflow\Memory\MemoryPoolInterface;

/**
 * Flag that uses a MemoryPoolInterface to store its state
 */
class MemoryFlag implements FlagInterface
{
    /**
     * @var MemoryPoolInterface
     */
    protected $memory_pool;

    /**
     * @var string
     */
    protected $key;

    /**
     * @param MemoryPoolInterface $memory_pool
     * @param string              $key
     * @param bool                $raised
     */
    public function __construct(MemoryPoolInterface $memory_pool, $key, $raised = false)
    {
        $this->memory_pool = $memory_pool;
        $this->key         = $key;

        if ($raised) {
            $this->raise();
        } else {
            $this->lower();
        }
    }

    /**
     * Raise the flag
     *
     * @return void
     */
    public function raise()
    {
        $this->memory_pool->set($this->key, '1');
    }

    /**
     * Lower the flag
     *
     * @return void
     */
    public function lower()
    {
        $this->memory_pool->set($this->key, '0');
    }

    /**
     * Check if the flag has been raised
     *
     * @return bool
     */
    public function isRaised()
    {
        return $this->memory_pool->get($this->key, '0') === '1';
    }
}
