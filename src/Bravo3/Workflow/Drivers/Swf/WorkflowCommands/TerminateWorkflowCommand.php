<?php
namespace Bravo3\Workflow\Drivers\Swf\WorkflowCommands;

use Bravo3\Workflow\Workflow\WorkflowSchema;

class TerminateWorkflowCommand extends AbstractWorkflowCommand
{
    /**
     * Terminate a workflow
     *
     * @return WorkflowSchema
     */
    public function execute()
    {
        $this->swf->terminateWorkflowExecution($this->createArgs());
    }

    /**
     * Create API call arguments
     *
     * @return array
     */
    protected function createArgs()
    {
        $args = [
            'domain'     => $this->workflow->getDomain(),
            'workflowId' => $this->getParameter('workflow_id', null, true),
            'reason'     => $this->getParameter('reason'),
            'details'    => $this->getParameter('details'),
        ];

        return $args;
    }
}
