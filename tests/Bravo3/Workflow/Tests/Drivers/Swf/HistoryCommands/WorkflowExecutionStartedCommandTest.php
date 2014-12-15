<?php
namespace Bravo3\Workflow\Tests\Drivers\Swf\HistoryCommands;

use Bravo3\Workflow\Drivers\Swf\HistoryCommands\WorkflowExecutionStartedCommand;
use Bravo3\Workflow\Workflow\WorkflowHistory;

class WorkflowExecutionStartedCommandTest extends \PHPUnit_Framework_TestCase
{
    public function testCommand()
    {
        $timestamp = 1326670266.115;
        $datestamp = new \DateTime();
        $datestamp->setTimestamp($timestamp);

        $attributes = ['input' => 'New Workflow'];
        $event_id   = 'test_'.rand(10000, 99999);

        $history = new WorkflowHistory();
        $command = new WorkflowExecutionStartedCommand($datestamp, $attributes, $event_id);

        $this->assertFalse($history->hasWorkflowFailed());
        $this->assertNull($history->getTimeStarted());
        $this->assertNull($history->getInput());

        $command->apply($history);

        $this->assertFalse($history->hasWorkflowFailed());
        $this->assertEquals('1326670266', $history->getTimeStarted()->format('U'));
        $this->assertEquals('New Workflow', $history->getInput());
        $this->assertCount(0, $history->getErrorMessages());
    }
}
