<?php
namespace Bravo3\Workflow\Tests\Drivers\Swf\HistoryCommands;

use Bravo3\Workflow\Drivers\Swf\HistoryCommands\ActivityTaskScheduledCommand;
use Bravo3\Workflow\Drivers\Swf\HistoryCommands\ActivityTaskStartedCommand;
use Bravo3\Workflow\Workflow\WorkflowHistory;

class ScheduleActivityTaskStartedCommandTest extends \PHPUnit_Framework_TestCase
{
    public function testCommand()
    {
        $timestamp = 1326670266.115;
        $datestamp = new \DateTime();
        $datestamp->setTimestamp($timestamp);

        $event_id = 'test_'.rand(10000, 99999);

        $attributes_scheduled = [
            'activityType' => ['name' => 'TestActivity', 'version' => 123],
            'input'        => 'Hello World',
            'control'      => '1234'
        ];

        $attributes_started = ['scheduledEventId' => $event_id];

        $history          = new WorkflowHistory();
        $schedule_command = new ActivityTaskScheduledCommand($datestamp, $attributes_scheduled, $event_id);
        $started_command  = new ActivityTaskStartedCommand($datestamp, $attributes_started, $event_id.'1');

        $this->assertCount(0, $history);
        $schedule_command->apply($history);

        $this->assertCount(1, $history);
        $event = $history->get($event_id);

        $this->assertEquals(1326670266, (int)$event->getTimeScheduled()->format('U'));
        $this->assertNull($event->getTimeStarted());
        $this->assertNull($event->getTimeEnded());

        $started_command->apply($history);
        $this->assertCount(1, $history);
        $this->assertEquals('Hello World', $event->getInput());

        $event = $history->get($event_id);
        $this->assertEquals('Hello World', $event->getInput());
        $this->assertEquals('1234', $event->getControl());
        $this->assertEquals(1326670266, (int)$event->getTimeScheduled()->format('U'));
        $this->assertEquals(1326670266, (int)$event->getTimeStarted()->format('U'));
        $this->assertNull($event->getTimeEnded());
        $this->assertFalse($history->hasWorkflowFailed());
    }

    /**
     * The history is out of order, the activity has not been scheduled yet
     *
     * @expectedException \Bravo3\Workflow\Exceptions\MalformedHistoryException
     */
    public function testCommandError()
    {
        $timestamp = 1326670266.115;
        $datestamp = new \DateTime();
        $datestamp->setTimestamp($timestamp);

        $attributes = ['scheduledEventId' => 123456];
        $event_id   = 'test_'.rand(10000, 99999);

        $history = new WorkflowHistory();
        $command = new ActivityTaskStartedCommand($datestamp, $attributes, $event_id);

        $command->apply($history);
    }
}
