<?php
namespace Bravo3\Workflow\Memory;

interface MemoryPoolInterface
{
    /**
     * @param string $namespace  Global prefix for all variables
     * @param int    $ttl        TTL for variable persistence in seconds
     * @param mixed  $parameters Parameters for the pool
     */
    public function __construct($namespace, $ttl, $parameters);

    /**
     * Set a variable
     *
     * @param string $key
     * @param mixed  $value
     * @return void
     */
    public function set($key, $value);

    /**
     * Get a variable
     *
     * @param string $key
     * @param mixed  $default
     * @return mixed
     */
    public function get($key, $default = null);

    /**
     * Delete a variable
     *
     * Does not throw an error if the variable does not exist.
     *
     * @param string $key
     * @return void
     */
    public function delete($key);
}
