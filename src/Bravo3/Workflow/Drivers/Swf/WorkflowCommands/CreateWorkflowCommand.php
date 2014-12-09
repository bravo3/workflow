<?php
namespace Bravo3\Workflow\Drivers\Swf\WorkflowCommands;

use Bravo3\Workflow\Exceptions\IoException;
use Bravo3\Workflow\Workflow\WorkflowSchema;

class CreateWorkflowCommand extends AbstractWorkflowCommand
{
    /**
     * Create a new workflow
     *
     * @return WorkflowSchema
     */
    public function execute()
    {
        $model = $this->swf->startWorkflowExecution($this->createArgs());

        $executionId = $this->getParameter('workflow_id');
        $runId       = $model->get('runId');

        if (!$executionId || !$runId) {
            throw new IoException("Unable to create new workflow");
        }

        return new WorkflowSchema($executionId, $runId);
    }

    /**
     * Create API call arguments
     *
     * @return array
     */
    protected function createArgs()
    {
        $args = [
            'domain'        => $this->workflow->getDomain(),
            'workflow_id'   => $this->getParameter('workflow_id', null, true),
            'workflow_type' => [
                'name'    => $this->workflow->getWorkflowName(),
                'version' => $this->workflow->getWorkflowVersion(),
            ],
        ];

        $tasklist = $this->getParameter('tasklist', $this->workflow->getTasklist());
        if ($tasklist) {
            $args['taskList'] = [
                'name' => $tasklist
            ];
        }

        $input = $this->getParameter('input');
        if ($input) {
            $args['input'] = $input;
        }

        $startToCloseTimeout = (int)$this->getParameter('start_to_close_timeout');
        if ($startToCloseTimeout) {
            $args['executionStartToCloseTimeout'] = $startToCloseTimeout;
        }

        return $args;
    }
}
