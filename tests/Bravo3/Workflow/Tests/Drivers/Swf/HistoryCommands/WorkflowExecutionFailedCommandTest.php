<?php
namespace Bravo3\Workflow\Tests\Drivers\Swf\HistoryCommands;

use Bravo3\Workflow\Drivers\Swf\HistoryCommands\WorkflowExecutionFailedCommand;
use Bravo3\Workflow\Workflow\WorkflowHistory;

class WorkflowExecutionFailedCommandTest extends \PHPUnit_Framework_TestCase
{
    public function testCommand()
    {
        $timestamp  = 1326670266.115;
        $attributes = ['reason' => 'Gremlins'];
        $event_id   = 'test_'.rand(10000, 99999);

        $history = new WorkflowHistory();
        $command = new WorkflowExecutionFailedCommand($timestamp, $attributes, $event_id);

        $this->assertFalse($history->hasWorkflowFailed());
        $this->assertNull($history->getTimeStarted());
        $this->assertNull($history->getInput());

        $command->apply($history);

        $this->assertTrue($history->hasWorkflowFailed());
        $this->assertEquals('1326670266', $history->getTimeEnded()->format('U'));
        $this->assertEquals('Failed: Gremlins', $history->getErrorMessages()[0]);
    }
}
