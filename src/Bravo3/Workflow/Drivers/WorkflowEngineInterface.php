<?php
namespace Bravo3\Workflow\Drivers;

use Bravo3\Workflow\Workflow\WorkflowAwareInterface;
use Bravo3\Workflow\Workflow\WorkflowSchema;
use Psr\Log\LoggerAwareInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

interface WorkflowEngineInterface extends EventDispatcherInterface, LoggerAwareInterface, WorkflowAwareInterface
{
    /**
     * Create a new workflow
     *
     * @param string $workflow_id Name of the workflow
     * @param string $input       Optional input to pass to the workflow
     * @return WorkflowSchema
     */
    public function createWorkflow($workflow_id, $input = null);

    /**
     * Terminate a workflow
     *
     * @param WorkflowSchema $workflow
     * @return void
     */
    public function terminateWorkflow(WorkflowSchema $workflow);
}
