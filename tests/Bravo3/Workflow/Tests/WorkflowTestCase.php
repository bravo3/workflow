<?php
namespace Bravo3\Workflow\Tests;

use Bravo3\Properties\Conf;
use Bravo3\Workflow\Memory\RedisMemoryPool;

abstract class WorkflowTestCase extends \PHPUnit_Framework_TestCase
{
    const TEST_NAMESPACE = 'test.';

    /**
     * @var Conf
     */
    protected $conf = null;

    /**
     * @var RedisMemoryPool
     */
    protected $redis_pool = null;

    /**
     * Get the Conf instance
     *
     * @return Conf
     */
    protected function getConf()
    {
        if (!$this->conf) {
            Conf::init(__DIR__.'/../../../config');
            $this->conf = Conf::getInstance();
        }

        return $this->conf;
    }

    protected function getRedisMemoryPool($ttl = 60)
    {
        $conf = $this->getConf();

        if (!$this->redis_pool) {
            $this->redis_pool = new RedisMemoryPool(
                self::TEST_NAMESPACE,
                $ttl,
                [
                    'host'     => $conf['redis.host'],
                    'port'     => $conf['redis.port'],
                    'database' => $conf['redis.database'],
                    'options'  => [],
                ]
            );
        }

        return $this->redis_pool;
    }
}
