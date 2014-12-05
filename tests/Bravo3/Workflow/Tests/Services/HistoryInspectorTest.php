<?php
namespace Bravo3\Workflow\Tests\Services;

use Bravo3\Workflow\Enum\HistoryItemState;
use Bravo3\Workflow\Services\HistoryInspector;
use Bravo3\Workflow\Task\TaskSchema;
use Bravo3\Workflow\Workflow\WorkflowHistory;
use Bravo3\Workflow\Workflow\WorkflowHistoryItem;

class HistoryInspectorTest extends \PHPUnit_Framework_TestCase
{
    public function testCounts()
    {
        $history = new WorkflowHistory();
        $history->add($this->createHistoryItem('test-activity', '1', HistoryItemState::COMPLETED(), 'alpha'));
        $history->add($this->createHistoryItem('test-activity', '1', HistoryItemState::COMPLETED(), 'bravo'));
        $history->add($this->createHistoryItem('test-activity', '2', HistoryItemState::COMPLETED(), 'charlie'));
        $history->add($this->createHistoryItem('test-activity', '1', HistoryItemState::SCHEDULED(), 'delta'));
        $history->add($this->createHistoryItem('other-activity', '1', HistoryItemState::COMPLETED(), 'echo'));
        $history->add($this->createHistoryItem('other-activity', '1', HistoryItemState::RUNNING(), 'foxtrot'));

        $inspector = new HistoryInspector($history);

        $this->assertEquals(0, $inspector->countActivityName('test-activity', HistoryItemState::RUNNING()));
        $this->assertEquals(0, $inspector->countActivityName('test-activity', HistoryItemState::FAILED()));
        $this->assertEquals(3, $inspector->countActivityName('test-activity', HistoryItemState::COMPLETED()));
        $this->assertEquals(1, $inspector->countActivityName('test-activity', HistoryItemState::SCHEDULED()));
        $this->assertEquals(1, $inspector->countActivityName('other-activity', HistoryItemState::COMPLETED()));
        $this->assertEquals(1, $inspector->countActivityName('other-activity', HistoryItemState::RUNNING()));
        $this->assertEquals(0, $inspector->countActivityName('other-activity', HistoryItemState::SCHEDULED()));
        $this->assertEquals(0, $inspector->countActivityName('other-activity', HistoryItemState::FAILED()));

        $this->assertEquals(1, $inspector->countControl('alpha', HistoryItemState::COMPLETED()));
        $this->assertEquals(1, $inspector->countControl('bravo', HistoryItemState::COMPLETED()));
        $this->assertEquals(1, $inspector->countControl('charlie', HistoryItemState::COMPLETED()));
        $this->assertEquals(0, $inspector->countControl('delta', HistoryItemState::COMPLETED()));
        $this->assertEquals(1, $inspector->countControl('echo', HistoryItemState::COMPLETED()));
        $this->assertEquals(0, $inspector->countControl('foxtrot', HistoryItemState::COMPLETED()));

        $this->assertTrue($inspector->haveOpenActivities());

        $alpha = new TaskSchema();
        $alpha->setActivityName('test-activity')->setActivityVersion('1')->setControl('alpha');

        $this->assertEquals(2, $inspector->countTask($alpha, HistoryItemState::COMPLETED()));
        $this->assertEquals(1, $inspector->countTask($alpha, HistoryItemState::SCHEDULED()));

        $omega = new TaskSchema();
        $omega->setActivityName('test-activity')->setActivityVersion('10');

        $this->assertEquals(0, $inspector->countTask($omega, HistoryItemState::COMPLETED()));

        $this->assertTrue($inspector->hasTaskBeenScheduled($alpha));
        $this->assertFalse($inspector->hasTaskBeenScheduled($omega));
    }

    protected $id = 1;

    protected function createHistoryItem($name, $version, HistoryItemState $state, $control)
    {
        $item = new WorkflowHistoryItem($this->id++);
        $item->setActivityName($name)->setActivityVersion($version);
        $item->setTimeScheduled(new \DateTime('2014-10-10 10:01:00'));
        $item->setTimeStarted(new \DateTime('2014-10-10 10:00:00'));
        $item->setTimeEnded(new \DateTime('2014-10-10 10:04:00'));
        $item->setState($state);
        $item->setControl($control)->setInput($control);
        return $item;
    }
}
 