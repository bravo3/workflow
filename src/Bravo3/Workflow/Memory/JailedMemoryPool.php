<?php
namespace Bravo3\Workflow\Memory;

class JailedMemoryPool implements MemoryPoolInterface
{
    /**
     * @var MemoryPoolInterface
     */
    protected $parent;

    /**
     * @var string
     */
    protected $namespace;

    /**
     * Create a jail for an existing memory pool
     *
     * @param MemoryPoolInterface $parent
     * @param string              $namespace
     * @return JailedMemoryPool
     */
    public static function jail(MemoryPoolInterface $parent, $namespace)
    {
        $pool = new JailedMemoryPool($namespace, null, null);
        $pool->setParent($parent);
        return $pool;
    }

    /**
     * Get Parent
     *
     * @return MemoryPoolInterface
     */
    protected function getParent()
    {
        return $this->parent;
    }

    /**
     * Set Parent
     *
     * @param MemoryPoolInterface $parent
     * @return $this
     */
    protected function setParent($parent)
    {
        $this->parent = $parent;
        return $this;
    }

    /**
     * Do not construct a jailed memory pool directly, instead call JailedMemoryPool::jail()
     *
     * @param string $namespace  Jailed sub-namespace
     * @param int    $ttl        Ignored, the parent TTL is used
     * @param mixed  $parameters Ignored, the parent parameters are used
     */
    public function __construct($namespace, $ttl, $parameters)
    {
        $this->namespace = $namespace;
    }

    /**
     * Set a variable
     *
     * @param string $key
     * @param mixed  $value
     * @return void
     */
    public function set($key, $value)
    {
        $this->parent->set($this->namespace.':'.$key, $value);
    }

    /**
     * Get a variable
     *
     * @param string $key
     * @param mixed  $default
     * @return mixed
     */
    public function get($key, $default = null)
    {
        return $this->parent->get($this->namespace.':'.$key, $default);
    }

    /**
     * Delete a variable
     *
     * Does not throw an error if the variable does not exist.
     *
     * @param string $key
     * @return void
     */
    public function delete($key)
    {
        $this->parent->delete($this->namespace.':'.$key);
    }
}
