<?php
namespace Bravo3\Workflow\Tests\Memory;

use Bravo3\Workflow\Memory\JailedMemoryPool;
use Bravo3\Workflow\Tests\WorkflowTestCase;

class JailedMemoryPoolTest extends WorkflowTestCase
{
    public function testGetSet()
    {
        $redis = $this->getRedisMemoryPool();
        $jail = JailedMemoryPool::jail($redis, 'test');

        $jail->set('hello', 'world');
        $this->assertEquals('world', $jail->get('hello', 'fake'));
        $this->assertEquals('world', $redis->get('test:hello', 'fake'));
    }

    /**
     * @group integration
     */
    public function testTTL()
    {
        $redis = $this->getRedisMemoryPool(1);
        $jail = JailedMemoryPool::jail($redis, 'test');

        $jail->set('hello', 'world');
        $this->assertEquals('world', $jail->get('hello', 'fake'));
        $this->assertEquals('world', $redis->get('test:hello', 'fake'));
        sleep(2);
        $this->assertEquals('fake', $jail->get('hello', 'fake'));
        $this->assertEquals('fake', $redis->get('test:hello', 'fake'));
    }

    public function testDel()
    {
        $redis = $this->getRedisMemoryPool();
        $jail = JailedMemoryPool::jail($redis, 'test');

        $redis->set('hello', 'world');
        $this->assertEquals('world', $jail->get('hello', 'fake'));
        $this->assertEquals('world', $redis->get('test:hello', 'fake'));
        $jail->delete('hello');
        $this->assertEquals('fake', $jail->get('hello', 'fake'));
        $this->assertEquals('fake', $redis->get('test:hello', 'fake'));
    }
}
 