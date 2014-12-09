<?php
namespace Bravo3\Workflow\Workflow;

class WorkflowSchema
{
    /**
     * @var string
     */
    protected $execution_id;

    /**
     * @var string
     */
    protected $run_id;

    public function __construct($execution_id = null, $run_id = null)
    {
        $this->execution_id = $execution_id;
        $this->run_id       = $run_id;
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
}
