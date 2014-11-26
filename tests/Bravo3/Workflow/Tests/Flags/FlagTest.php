<?php
namespace Bravo3\Workflow\Tests\Flags;

use Bravo3\Workflow\Flags\FlagInterface;
use Bravo3\Workflow\Flags\MemoryFlag;
use Bravo3\Workflow\Flags\SimpleFlag;
use Bravo3\Workflow\Tests\WorkflowTestCase;

class RedisMemoryPoolTest extends WorkflowTestCase
{
    /**
     * @dataProvider flagProvider
     */
    public function testFlag(FlagInterface $flag, $default_state)
    {
        $this->assertEquals($default_state, $flag->isRaised());
        $flag->lower();
        $this->assertFalse($flag->isRaised());
        $flag->raise();
        $this->assertTrue($flag->isRaised());
        $flag->lower();
        $this->assertFalse($flag->isRaised());
    }

    public function flagProvider()
    {
        return [
            [new SimpleFlag(), false],
            [new SimpleFlag(true), true],
            [new MemoryFlag($this->getRedisMemoryPool(), 'flag.test.1'), false],
            [new MemoryFlag($this->getRedisMemoryPool(), 'flag.test.2', true), true],
        ];
    }
}
