<?php
namespace Bravo3\Workflow\Tests\Memory;

use Bravo3\Workflow\Tests\WorkflowTestCase;

class RedisMemoryPoolTest extends WorkflowTestCase
{
    public function testGetSet()
    {
        $redis = $this->getRedisMemoryPool();
        $redis->set('hello', 'world');
        $this->assertEquals('world', $redis->get('hello', 'fake'));
    }

    /**
     * @group integration
     */
    public function testTTL()
    {
        $redis = $this->getRedisMemoryPool(1);
        $redis->set('hello', 'world');
        $this->assertEquals('world', $redis->get('hello', 'fake'));
        sleep(2);
        $this->assertEquals('fake', $redis->get('hello', 'fake'));
    }

    public function testDel()
    {
        $redis = $this->getRedisMemoryPool();
        $redis->set('hello', 'world');
        $this->assertEquals('world', $redis->get('hello', 'fake'));
        $redis->delete('hello');
        $this->assertEquals('fake', $redis->get('hello', 'fake'));
    }
}
