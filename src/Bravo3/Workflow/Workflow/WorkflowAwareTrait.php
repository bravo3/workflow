<?php
namespace Bravo3\Workflow\Workflow;

trait WorkflowAwareTrait
{
    /**
     * @var WorkflowInterface
     */
    protected $workflow;

    /**
     * Get the workflow
     *
     * @return WorkflowInterface
     */
    public function getWorkflow()
    {
        return $this->workflow;
    }

    /**
     * Set the workflow
     *
     * @param WorkflowInterface $workflow
     * @return $this
     */
    public function setWorkflow(WorkflowInterface $workflow)
    {
        $this->workflow = $workflow;
        return $this;
    }
}
