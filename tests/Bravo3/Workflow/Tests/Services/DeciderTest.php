<?php
namespace Bravo3\Workflow\Tests\Services;

use Bravo3\Properties\Conf;
use Bravo3\Workflow\Enum\HistoryItemState;
use Bravo3\Workflow\Enum\WorkflowResult;
use Bravo3\Workflow\Events\DecisionEvent;
use Bravo3\Workflow\Memory\RedisMemoryPool;
use Bravo3\Workflow\Services\Decider;
use Bravo3\Workflow\Workflow\WorkflowHistoryItem;
use Bravo3\Workflow\Workflow\YamlWorkflow;

class DeciderTest extends \PHPUnit_Framework_TestCase
{
    public function testDecider()
    {
        Conf::init(__DIR__.'/../../../../config/');

        $memory_pool = new RedisMemoryPool('decider-tests', 60, Conf::get('redis'));

        $decider = new Decider();
        $decider->setWorkflow(new YamlWorkflow(__DIR__.'/../Resources/TestSchema.yml'));
        $decider->setMemoryPool($memory_pool);

        // Workflow started -
        $event2 = new DecisionEvent();

        $decider->processDecisionEvent($event2);
        $this->assertCount(1, $event2->getDecision()->getScheduledTasks());
        $this->assertEquals(WorkflowResult::COMMAND(), $event2->getDecision()->getWorkflowResult());

        // Task 1 complete -
        $alpha = new WorkflowHistoryItem('1');
        $alpha->setActivityName('test-activity')->setActivityVersion('1');
        $alpha->setTimeScheduled(new \DateTime('2014-10-10 10:01:00'));
        $alpha->setTimeStarted(new \DateTime('2014-10-10 10:00:00'));
        $alpha->setTimeEnded(new \DateTime('2014-10-10 10:04:00'));
        $alpha->setState(HistoryItemState::COMPLETED());
        $alpha->setControl('alpha')->setInput('alpha')->setResult("Hello World");

        $event2 = new DecisionEvent();
        $event2->getHistory()->add($alpha);

        $decider->processDecisionEvent($event2);
        $this->assertCount(1, $event2->getDecision()->getScheduledTasks());
        $this->assertEquals(WorkflowResult::COMMAND(), $event2->getDecision()->getWorkflowResult());
    }

}
