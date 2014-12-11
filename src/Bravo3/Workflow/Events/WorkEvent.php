<?php
namespace Bravo3\Workflow\Events;

class WorkEvent extends WorkflowEvent
{
    /**
     * @var string
     */
    protected $activity_id;

    /**
     * @var string
     */
    protected $input;

    /**
     * @var string
     */
    protected $control;

    /**
     * @var string
     */
    protected $activity_name;

    /**
     * @var string
     */
    protected $activity_version;

    /**
     * Get ActivityId
     *
     * @return string
     */
    public function getActivityId()
    {
        return $this->activity_id;
    }

    /**
     * Set ActivityId
     *
     * @param string $activity_id
     * @return $this
     */
    public function setActivityId($activity_id)
    {
        $this->activity_id = $activity_id;
        return $this;
    }

    /**
     * Get Input
     *
     * @return string
     */
    public function getInput()
    {
        return $this->input;
    }

    /**
     * Set Input
     *
     * @param string $input
     * @return $this
     */
    public function setInput($input)
    {
        $this->input = $input;
        return $this;
    }

    /**
     * Get ActivityName
     *
     * @return string
     */
    public function getActivityName()
    {
        return $this->activity_name;
    }

    /**
     * Set ActivityName
     *
     * @param string $activity_name
     * @return $this
     */
    public function setActivityName($activity_name)
    {
        $this->activity_name = $activity_name;
        return $this;
    }

    /**
     * Get ActivityVersion
     *
     * @return string
     */
    public function getActivityVersion()
    {
        return $this->activity_version;
    }

    /**
     * Set ActivityVersion
     *
     * @param string $activity_version
     * @return $this
     */
    public function setActivityVersion($activity_version)
    {
        $this->activity_version = $activity_version;
        return $this;
    }

    /**
     * Get Control
     *
     * @return string
     */
    public function getControl()
    {
        return $this->control;
    }

    /**
     * Set Control
     *
     * @param string $control
     * @return $this
     */
    public function setControl($control)
    {
        $this->control = $control;
        return $this;
    }
}
