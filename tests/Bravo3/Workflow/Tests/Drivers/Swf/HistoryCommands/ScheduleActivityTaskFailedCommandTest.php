<?php
namespace Bravo3\Workflow\Tests\Drivers\Swf\HistoryCommands;

use Bravo3\Workflow\Drivers\Swf\HistoryCommands\ScheduleActivityTaskFailedCommand;
use Bravo3\Workflow\Workflow\WorkflowHistory;

class ScheduleActivityTaskFailedCommandTest extends \PHPUnit_Framework_TestCase
{
    public function testCommand()
    {
        $timestamp = 1326670266.115;
        $datestamp = new \DateTime();
        $datestamp->setTimestamp($timestamp);

        $attributes = ['activityType' => ['name' => 'TestActivity', 'version' => 123], 'cause' => 'Gremlins'];
        $event_id = 'test_'.rand(10000, 99999);

        $history = new WorkflowHistory();
        $command = new ScheduleActivityTaskFailedCommand($datestamp, $attributes, $event_id);

        $this->assertFalse($history->hasWorkflowFailed());
        $this->assertNull($history->getTimeStarted());
        $this->assertNull($history->getInput());

        $command->apply($history);

        // When a schedule fails, we should mark the workflow as critically failed, but it still hasn't ended
        // This should act as an indication to fail the entire workflow
        $this->assertTrue($history->hasWorkflowFailed());
        $this->assertNull($history->getTimeEnded());
        $this->assertEquals(
            'Unable to schedule activity task: TestActivity-123 (Gremlins)',
            $history->getErrorMessages()[0]
        );
    }
}
