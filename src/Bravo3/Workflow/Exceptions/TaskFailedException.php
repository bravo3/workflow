<?php
namespace Bravo3\Workflow\Exceptions;

use Bravo3\Workflow\Events\WorkEvent;

class TaskFailedException extends \RuntimeException implements WorkflowException
{
    /**
     * @var WorkEvent
     */
    protected $event;

    public function __construct($reason, WorkEvent $event)
    {
        $this->event = $event;
        parent::__construct($reason);
    }

    /**
     * Get the work event
     *
     * @return WorkEvent
     */
    public function getWorkEvent()
    {
        return $this->event;
    }
}
