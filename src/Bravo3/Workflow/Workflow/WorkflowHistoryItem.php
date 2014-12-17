<?php
namespace Bravo3\Workflow\Workflow;

use Bravo3\Workflow\Enum\HistoryItemState;

/**
 * An event in a workflow, such as running a task
 */
class WorkflowHistoryItem
{
    /**
     * @var string
     */
    protected $event_id;

    /**
     * @var string
     */
    protected $activity_name;

    /**
     * @var string
     */
    protected $activity_version;

    /**
     * @var \DateTime
     */
    protected $time_scheduled;

    /**
     * @var \DateTime
     */
    protected $time_started;

    /**
     * @var \DateTime
     */
    protected $time_ended;

    /**
     * @var HistoryItemState
     */
    protected $state;

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
    protected $result;

    /**
     * @var string
     */
    protected $worker_id;

    /**
     * @var string
     */
    protected $error_message = null;

    public function __construct($event_id = null)
    {
        $this->event_id = $event_id;
    }

    /**
     * Check if this history item is still pending
     *
     * @return bool
     */
    public function isPending()
    {
        return $this->state == HistoryItemState::SCHEDULED();
    }

    /**
     * Check if this history item completed successfully
     *
     * @return bool
     */
    public function isSuccess()
    {
        return $this->state == HistoryItemState::COMPLETED();
    }

    /**
     * Check if this history item is a actively running
     *
     * @return bool
     */
    public function isActive()
    {
        return $this->state == HistoryItemState::RUNNING();
    }

    /**
     * Check if this history item is a failure
     *
     * Includes failing, cancelling and timing out.
     *
     * @return bool
     */
    public function isFailure()
    {
        switch ($this->state) {
            case HistoryItemState::CANCELLED():
            case HistoryItemState::FAILED():
            case HistoryItemState::TIMED_OUT():
                return true;
            default:
                return false;
        }
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
     * Get State
     *
     * @return HistoryItemState
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set State
     *
     * @param HistoryItemState $state
     * @return $this
     */
    public function setState($state)
    {
        $this->state = $state;
        return $this;
    }

    /**
     * Get the time the event ended
     *
     * @return \DateTime
     */
    public function getTimeEnded()
    {
        return $this->time_ended;
    }

    /**
     * Set the time the event ended
     *
     * @param \DateTime $time_ended
     * @return $this
     */
    public function setTimeEnded($time_ended)
    {
        $this->time_ended = $time_ended;
        return $this;
    }

    /**
     * Get the time the event started
     *
     * @return \DateTime
     */
    public function getTimeStarted()
    {
        return $this->time_started;
    }

    /**
     * Set the time the event started
     *
     * @param \DateTime $time_started
     * @return $this
     */
    public function setTimeStarted($time_started)
    {
        $this->time_started = $time_started;
        return $this;
    }

    /**
     * Get the time the event was scheduled
     *
     * @return \DateTime
     */
    public function getTimeScheduled()
    {
        return $this->time_scheduled;
    }

    /**
     * Set the time the event was scheduled
     *
     * @param \DateTime $time_scheduled
     * @return $this
     */
    public function setTimeScheduled($time_scheduled)
    {
        $this->time_scheduled = $time_scheduled;
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

    /**
     * A combination of the activity name and version
     *
     * @return string
     */
    public function getActivityKey()
    {
        return $this->getActivityName().'/'.$this->getActivityVersion();
    }

    /**
     * Get the unique ID for this specific activity
     *
     * @return string
     */
    public function getEventId()
    {
        return $this->event_id;
    }

    /**
     * Set the unique ID for this specific activity
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
     * Get WorkerId
     *
     * @return string
     */
    public function getWorkerId()
    {
        return $this->worker_id;
    }

    /**
     * Set WorkerId
     *
     * @param string $worker_id
     * @return $this
     */
    public function setWorkerId($worker_id)
    {
        $this->worker_id = $worker_id;
        return $this;
    }

    /**
     * Get the error message for failure
     *
     * @return string
     */
    public function getErrorMessage()
    {
        return $this->error_message;
    }

    /**
     * Set the error message for failure
     *
     * @param string $error_message
     * @return $this
     */
    public function setErrorMessage($error_message)
    {
        $this->error_message = $error_message;
        return $this;
    }
}
