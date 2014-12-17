<?php
namespace Bravo3\Workflow\Workflow;

use Bravo3\Workflow\Enum\HistoryItemState;
use Bravo3\Workflow\Exceptions\OutOfBoundsException;
use Bravo3\Workflow\Exceptions\UnexpectedValueException;

/**
 * The history of a workflow, containing many history items
 */
class WorkflowHistory implements \IteratorAggregate, \ArrayAccess, \Countable
{
    /**
     * @var string
     */
    protected $input;

    /**
     * @var \DateTime
     */
    protected $time_started;

    /**
     * @var \DateTime
     */
    protected $time_ended;

    /**
     * @var WorkflowHistoryItem[]
     */
    protected $history = [];

    /**
     * @var int
     */
    protected $iterator_position = 0;

    /**
     * @var bool
     */
    protected $activity_error = false;

    /**
     * @var bool
     */
    protected $fatal_error = false;

    /**
     * @var string[]
     */
    protected $error_messages = [];

    /**
     * Add a history item
     *
     * Adding an item in a failed state will flag the workflow as having a failed activity. If you modify the item post
     * being added, you should set the fail flag manually.
     *
     * @param WorkflowHistoryItem $item
     * @return $this
     */
    public function add(WorkflowHistoryItem $item)
    {
        $this->history[$item->getEventId()] = $item;
        $this->flagActivityFailure($item);
        return $this;
    }

    /**
     * If the passed item is in a failed state, automatically flag this workflow as having a failed activity
     *
     * @param WorkflowHistoryItem $item
     */
    protected function flagActivityFailure(WorkflowHistoryItem $item)
    {
        switch ($item->getState()) {
            case HistoryItemState::FAILED():
            case HistoryItemState::TIMED_OUT():
                $this->setActivityFailed();
                $this->error_messages[] = $item->getErrorMessage();
            default:
                return;
        }
    }

    /**
     * Retrieve a history item
     *
     * @param $event_id
     * @return WorkflowHistoryItem
     */
    public function get($event_id)
    {
        if (!$this->offsetExists($event_id)) {
            throw new OutOfBoundsException("Event '".$event_id."' does not exist in the history");
        }

        return $this->history[$event_id];
    }

    /**
     * Get workflow input
     *
     * @return string
     */
    public function getInput()
    {
        return $this->input;
    }

    /**
     * Set workflow input
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
     * Get TimeEnded
     *
     * @return \DateTime
     */
    public function getTimeEnded()
    {
        return $this->time_ended;
    }

    /**
     * Set TimeEnded
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
     * Get TimeStarted
     *
     * @return \DateTime
     */
    public function getTimeStarted()
    {
        return $this->time_started;
    }

    /**
     * Set TimeStarted
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
     * Get an iterator for history items
     *
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->history);
    }

    /**
     * Check if there has been a fatal error with the workflow, such as a schedule failing
     *
     * @return bool
     */
    public function hasWorkflowFailed()
    {
        return $this->fatal_error;
    }

    /**
     * Check if an activity has failed
     *
     * @return boolean
     */
    public function hasActivityFailure()
    {
        return $this->activity_error;
    }

    /**
     * Mark the workflow as having a failed activity
     *
     * @return $this
     */
    public function setActivityFailed()
    {
        $this->activity_error = true;
        return $this;
    }

    /**
     * Get the error messages relating to the activities or workflow state that may have caused the failure
     *
     * @return string[]
     */
    public function getErrorMessages()
    {
        return $this->error_messages;
    }

    /**
     * Mark the workflow as critically failed
     *
     * @param string $message
     */
    public function setWorkflowFailed($message)
    {
        $this->fatal_error      = true;
        $this->error_messages[] = $message;
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Whether a offset exists
     *
     * @link http://php.net/manual/en/arrayaccess.offsetexists.php
     * @param mixed $offset <p>
     *                      An offset to check for.
     *                      </p>
     * @return boolean true on success or false on failure.
     *                      </p>
     *                      <p>
     *                      The return value will be casted to boolean if non-boolean was returned.
     */
    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->history);
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Offset to retrieve
     *
     * @link http://php.net/manual/en/arrayaccess.offsetget.php
     * @param mixed $offset <p>
     *                      The offset to retrieve.
     *                      </p>
     * @return mixed Can return all value types.
     */
    public function offsetGet($offset)
    {
        if (!$this->offsetExists($offset)) {
            throw new OutOfBoundsException("Event '".$offset."' does not exist in the history");
        }

        return $this->get($offset);
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Offset to set
     *
     * @link http://php.net/manual/en/arrayaccess.offsetset.php
     * @param mixed $offset <p>
     *                      The offset to assign the value to.
     *                      </p>
     * @param mixed $value  <p>
     *                      The value to set.
     *                      </p>
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        if (!($value instanceof WorkflowHistoryItem)) {
            throw new UnexpectedValueException("You can only assign WorkflowHistoryItems to WorkflowHistory objects");
        }

        if ($offset && ($offset !== $value->getEventId())) {
            throw new UnexpectedValueException("Offset does not match event ID");
        }

        $this->add($value);
    }

    /**
     * (PHP 5 &gt;= 5.0.0)<br/>
     * Offset to unset
     *
     * @link http://php.net/manual/en/arrayaccess.offsetunset.php
     * @param mixed $offset <p>
     *                      The offset to unset.
     *                      </p>
     * @return void
     */
    public function offsetUnset($offset)
    {
        unset($this->history[$offset]);
    }

    /**
     * (PHP 5 &gt;= 5.1.0)<br/>
     * Count elements of an object
     *
     * @link http://php.net/manual/en/countable.count.php
     * @return int The custom count as an integer.
     *       </p>
     *       <p>
     *       The return value is cast to an integer.
     */
    public function count()
    {
        return count($this->history);
    }
}
