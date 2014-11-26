<?php
namespace Bravo3\Workflow\Exceptions;

use Bravo3\Workflow\Workflow\WorkflowHistory;

class MalformedHistoryException extends \RuntimeException implements WorkflowException
{
    /**
     * @var WorkflowHistory
     */
    protected $history;

    /**
     * @var string
     */
    protected $event_id;

    function __construct(WorkflowHistory $history, $event_id, \Exception $previous = null)
    {
        $this->event_id = $event_id;
        $this->history  = $history;
        parent::__construct("Workflow history is out of order or malformed", 0, $previous);
    }

    /**
     * Get the event ID that has not been correctly scheduled
     *
     * @return string
     */
    public function getEventId()
    {
        return $this->event_id;
    }

    /**
     * Get the WorkflowHistory object
     *
     * @return WorkflowHistory
     */
    public function getHistory()
    {
        return $this->history;
    }
}
