<?php
namespace Bravo3\Workflow\Tests\Workflow;

use Bravo3\Workflow\Workflow\YamlWorkflow;

class YamlWorkflowTest extends \PHPUnit_Framework_TestCase
{
    public function testLoader()
    {
        $workflow = new YamlWorkflow(__DIR__.'/../Resources/TestSchema.yml');

        $this->assertEquals('test', $workflow->getTasklist());
        $this->assertEquals(60, $workflow->getStartToCloseTimeout());

        $tasks = $workflow->getTasks();
        $this->assertCount(4, $tasks);

        $alpha = $tasks[0];
        $this->assertEquals('alpha', $alpha->getControl());
        $this->assertEquals('test-activity', $alpha->getActivityName());
        $this->assertEquals('1', $alpha->getActivityVersion());
    }
}
