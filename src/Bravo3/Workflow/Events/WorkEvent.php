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
}
