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
    public function testSampleWorkflow()
    {
        Conf::init(__DIR__.'/../../../../config/');

        $memory_pool = new RedisMemoryPool('decider-tests', 60, Conf::get('redis'));

        $decider = new Decider();
        $decider->setWorkflow(new YamlWorkflow(__DIR__.'/../Resources/TestSchema.yml'));
        $decider->setMemoryPool($memory_pool);

        // Workflow started -
        $event1 = new DecisionEvent();
        $decider->processDecisionEvent($event1);

        $this->assertCount(1, $event1->getDecision()->getScheduledTasks());
        $this->assertEquals(WorkflowResult::COMMAND(), $event1->getDecision()->getWorkflowResult());
        $task = $event1->getDecision()->getScheduledTasks()[0];
        $this->assertEquals('alpha', $task->getControl());

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
        $task = $event2->getDecision()->getScheduledTasks()[0];
        $this->assertEquals('bravo', $task->getControl());

        // Task 2 complete -
        $bravo = new WorkflowHistoryItem('2');
        $bravo->setActivityName('test-activity')->setActivityVersion('1');
        $bravo->setTimeScheduled(new \DateTime('2014-11-10 10:01:00'));
        $bravo->setTimeStarted(new \DateTime('2014-11-10 10:00:00'));
        $bravo->setTimeEnded(new \DateTime('2014-11-10 10:04:00'));
        $bravo->setState(HistoryItemState::COMPLETED());
        $bravo->setControl('bravo')->setInput('bravo')->setResult("Hello World");

        $event3 = new DecisionEvent();
        $event3->getHistory()->add($alpha);
        $event3->getHistory()->add($bravo);
        $decider->processDecisionEvent($event3);

        $this->assertCount(1, $event3->getDecision()->getScheduledTasks());
        $this->assertEquals(WorkflowResult::COMMAND(), $event3->getDecision()->getWorkflowResult());
        $task = $event3->getDecision()->getScheduledTasks()[0];
        $this->assertEquals('charlie', $task->getControl());

        // Task 3 complete -
        $charlie = new WorkflowHistoryItem('3');
        $charlie->setActivityName('test-activity')->setActivityVersion('1');
        $charlie->setTimeScheduled(new \DateTime('2014-11-10 10:01:00'));
        $charlie->setTimeStarted(new \DateTime('2014-11-10 10:00:00'));
        $charlie->setTimeEnded(new \DateTime('2014-11-10 10:04:00'));
        $charlie->setState(HistoryItemState::COMPLETED());
        $charlie->setControl('charlie')->setInput('charlie')->setResult("Hello World");

        $event4 = new DecisionEvent();
        $event4->getHistory()->add($alpha);
        $event4->getHistory()->add($bravo);
        $event4->getHistory()->add($charlie);

        $decider->processDecisionEvent($event4);
        $this->assertCount(0, $event4->getDecision()->getScheduledTasks());
        $this->assertEquals(WorkflowResult::COMPLETE(), $event4->getDecision()->getWorkflowResult());
    }

}
