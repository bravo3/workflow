<?php
namespace Bravo3\Workflow\Workflow;

use Bravo3\Workflow\Enum\WorkflowResult;
use Bravo3\Workflow\Task\TaskSchema;

class Decision
{
    /**
     * @var TaskSchema[]
     */
    protected $scheduled_tasks = [];

    /**
     * @var WorkflowResult
     */
    protected $workflow_result;

    /**
     * @var string
     */
    protected $decision_token;

    public function __construct($token)
    {
        $this->decision_token = $token;
        $this->workflow_result = WorkflowResult::COMMAND();
    }

    /**
     * Get ScheduledTasks
     *
     * @return TaskSchema[]
     */
    public function getScheduledTasks()
    {
        return $this->scheduled_tasks;
    }

    /**
     * Set ScheduledTasks
     *
     * @param TaskSchema[] $scheduled_tasks
     * @return $this
     */
    public function setScheduledTasks(array $scheduled_tasks)
    {
        $this->scheduled_tasks = $scheduled_tasks;
        return $this;
    }

    /**
     * Get WorkflowResult
     *
     * @return WorkflowResult
     */
    public function getWorkflowResult()
    {
        return $this->workflow_result;
    }

    /**
     * Set WorkflowResult
     *
     * @param WorkflowResult $workflow_result
     * @return $this
     */
    public function setWorkflowResult($workflow_result)
    {
        $this->workflow_result = $workflow_result;
        return $this;
    }

    /**
     * Add a new task to the schedule
     *
     * @param TaskSchema $task
     * @return $this
     */
    public function scheduledTask(TaskSchema $task)
    {
        $this->scheduled_tasks[] = $task;
        return $this;
    }

    /**
     * Get the token of the decision task
     *
     * @return string
     */
    public function getDecisionToken()
    {
        return $this->decision_token;
    }
}
