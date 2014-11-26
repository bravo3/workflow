<?php
namespace Bravo3\Workflow\Events;

use Symfony\Component\EventDispatcher\Event;

abstract class WorkflowEvent extends Event
{
    /**
     * @var string
     */
    protected $event_id;

    /**
     * @var string
     */
    protected $token;

    /**
     * @var string
     */
    protected $workflow_name;

    /**
     * @var string
     */
    protected $workflow_version;

    /**
     * @var string
     */
    protected $execution_id;

    /**
     * @var string
     */
    protected $run_id;

    /**
     * Get EventId
     *
     * @return string
     */
    public function getEventId()
    {
        return $this->event_id;
    }

    /**
     * Set EventId
     *
     * @param string $event_id
     * @return $this
     */
    public function setEventId($event_id)
    {
        $this->event_id = $event_id;
        return $this;
    }

    /**
     * Get ExecutionId
     *
     * @return string
     */
    public function getExecutionId()
    {
        return $this->execution_id;
    }

    /**
     * Set ExecutionId
     *
     * @param string $execution_id
     * @return $this
     */
    public function setExecutionId($execution_id)
    {
        $this->execution_id = $execution_id;
        return $this;
    }

    /**
     * Get RunId
     *
     * @return string
     */
    public function getRunId()
    {
        return $this->run_id;
    }

    /**
     * Set RunId
     *
     * @param string $run_id
     * @return $this
     */
    public function setRunId($run_id)
    {
        $this->run_id = $run_id;
        return $this;
    }

    /**
     * Get Token
     *
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Set Token
     *
     * @param string $token
     * @return $this
     */
    public function setToken($token)
    {
        $this->token = $token;
        return $this;
    }

    /**
     * Get WorkflowName
     *
     * @return string
     */
    public function getWorkflowName()
    {
        return $this->workflow_name;
    }

    /**
     * Set WorkflowName
     *
     * @param string $workflow_name
     * @return $this
     */
    public function setWorkflowName($workflow_name)
    {
        $this->workflow_name = $workflow_name;
        return $this;
    }

    /**
     * Get WorkflowVersion
     *
     * @return string
     */
    public function getWorkflowVersion()
    {
        return $this->workflow_version;
    }

    /**
     * Set WorkflowVersion
     *
     * @param string $workflow_version
     * @return $this
     */
    public function setWorkflowVersion($workflow_version)
    {
        $this->workflow_version = $workflow_version;
        return $this;
    }
}
