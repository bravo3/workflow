<?php
namespace Bravo3\Workflow\Memory;

use Predis\Client;

class RedisMemoryPool implements MemoryPoolInterface
{
    /**
     * @var string
     */
    protected $namespace;

    /**
     * @var int
     */
    protected $ttl;

    /**
     * @var Client
     */
    protected $client;

    /**
     * @param string $namespace  Global prefix for all variables
     * @param int    $ttl        TTL for variable persistence in seconds
     * @param mixed  $parameters Parameters for the pool
     */
    public function __construct($namespace, $ttl, $parameters)
    {
        if (isset($parameters['options'])) {
            $options = $parameters['options'];
            unset($parameters['options']);
        } else {
            $options = null;
        }

        $this->namespace = $namespace;
        $this->ttl       = $ttl;
        $this->client    = new Client($parameters, $options);
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
        $this->client->set($this->namespace.$key, $value, 'EX', $this->ttl);
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
        if ($this->client->exists($this->namespace.$key)) {
            return $this->client->get($this->namespace.$key);
        } else {
            return $default;
        }
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
        $this->client->del([$this->namespace.$key]);
    }
}
