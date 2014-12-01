<?php
namespace Bravo3\Workflow\Services;

use Bravo3\Workflow\Workflow\YamlWorkflow;

class WorkflowService
{
    /**
     * @var YamlWorkflow
     */
    protected $workflow;

    public function __construct(YamlWorkflow $workflow)
    {
        $this->workflow = $workflow;
    }
}
