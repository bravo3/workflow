<?php
namespace Bravo3\Workflow\Workflow;

interface WorkflowAwareInterface
{
    /**
     * Get the workflow
     *
     * @return WorkflowInterface
     */
    public function getWorkflow();

    /**
     * Set the workflow
     *
     * @param WorkflowInterface $workflow
     * @return $this
     */
    public function setWorkflow(WorkflowInterface $workflow);
}
