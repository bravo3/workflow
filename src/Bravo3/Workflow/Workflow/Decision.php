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

    /**
     * @var string
     */
    protected $result;

    /**
     * @var string
     */
    protected $reason;

    /**
     * @var string
     */
    protected $details;

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

    /**
     * Get Details
     *
     * @return string
     */
    public function getDetails()
    {
        return $this->details;
    }

    /**
     * Set Details
     *
     * @param string $details
     * @return $this
     */
    public function setDetails($details)
    {
        $this->details = $details;
        return $this;
    }

    /**
     * Get Reason
     *
     * @return string
     */
    public function getReason()
    {
        return $this->reason;
    }

    /**
     * Set Reason
     *
     * @param string $reason
     * @return $this
     */
    public function setReason($reason)
    {
        $this->reason = $reason;
        return $this;
    }

    /**
     * Get Result
     *
     * @return string
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * Set Result
     *
     * @param string $result
     * @return $this
     */
    public function setResult($result)
    {
        $this->result = $result;
        return $this;
    }
}
