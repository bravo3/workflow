<?php
namespace Bravo3\Workflow\Drivers\Swf;

use Aws\Common\Aws;
use Aws\Swf\SwfClient;
use Bravo3\Workflow\Drivers\Swf\WorkflowCommands\CreateWorkflowCommand;
use Bravo3\Workflow\Drivers\WorkflowEngineInterface;
use Bravo3\Workflow\Enum\Event;
use Bravo3\Workflow\Events\WorkflowSchemaEvent;
use Bravo3\Workflow\Workflow\WorkflowAwareTrait;
use Bravo3\Workflow\Workflow\WorkflowSchema;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\NullLogger;
use Symfony\Component\EventDispatcher\EventDispatcher;

class SwfWorkflowEngine extends EventDispatcher implements WorkflowEngineInterface
{
    use WorkflowAwareTrait;
    use LoggerAwareTrait;

    /**
     * @var SwfClient
     */
    protected $swf;

    /**
     * Create an SWF workflow engine
     *
     * $swf_config contains:
     *      'domain' => (string) SWF domain (required)
     *      'tasklist' => (string) SWF tasklist to pull a decision/work task from (required)
     *      'identity' => (string) Identity of the decider to pass back to SWF
     *
     * @param array $aws_config AWS connection parameters
     */
    public function __construct(array $aws_config)
    {
        $aws          = Aws::factory($aws_config);
        $this->swf    = $aws->get('swf');
        $this->logger = new NullLogger();
    }

    /**
     * Create a new workflow
     *
     * @param string $workflow_id
     * @param string $input
     * @return WorkflowSchema
     */
    public function createWorkflow($workflow_id, $input = null)
    {
        $this->logger->info("Creating workflow: ".$workflow_id." with input '".$input."'");

        $cmd = new CreateWorkflowCommand(
            $this->swf, $this->getWorkflow(), [
                'workflow_id' => $workflow_id,
                'input'       => $input,
                'tasklist'    => $this->getWorkflow()->getTasklist(),
            ]
        );

        $schema = $cmd->execute();
        $this->dispatch(Event::WORKFLOW_CREATED, new WorkflowSchemaEvent($schema));

        return $schema;
    }

    /**
     * Terminate a workflow
     *
     * @param WorkflowSchema $workflow
     * @return void
     */
    public function terminateWorkflow(WorkflowSchema $workflow)
    {
        $this->logger->info("Terminating workflow: ".$workflow->getExecutionId()."/".$workflow->getRunId());

        // TODO: Implement terminateWorkflow() method.
        $this->logger->warning("(not implemented)");

        //$this->dispatch(Event::WORKFLOW_TERMINATED, new WorkflowSchemaEvent($schema));
    }
}
