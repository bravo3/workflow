<?php
namespace Bravo3\Workflow\Workflow;

use Bravo3\Workflow\Task\TaskSchema;

interface WorkflowInterface
{
    /**
     * Get all task schemas in the workflow
     *
     * @return TaskSchema[]
     */
    public function getTasks();

    /**
     * Get the domain that the workflow resides in
     *
     * @return string
     */
    public function getDomain();

    /**
     * Get the decision tasklist
     *
     * @return string
     */
    public function getTasklist();

    /**
     * Start to close timeout of the entire workflow in seconds
     *
     * @return int
     */
    public function getStartToCloseTimeout();
}
