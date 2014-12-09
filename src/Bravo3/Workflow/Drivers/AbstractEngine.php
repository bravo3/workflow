<?php
namespace Bravo3\Workflow\Drivers;

use Bravo3\Workflow\Enum\Event;
use Bravo3\Workflow\Events\PollingEvent;
use Bravo3\Workflow\Events\WorkflowEvent;
use Bravo3\Workflow\Flags\FlagInterface;
use Bravo3\Workflow\Flags\SimpleFlag;
use Bravo3\Workflow\Workflow\WorkflowAwareTrait;
use Bravo3\Workflow\Workflow\WorkflowInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Symfony\Component\EventDispatcher\EventDispatcher;

abstract class AbstractEngine extends EventDispatcher
{
    use WorkflowAwareTrait;
    use LoggerAwareTrait;

    /**
     * @var FlagInterface
     */
    protected $abort_flag;

    /**
     * @var WorkflowInterface
     */
    protected $workflow;

    /**
     * @var string
     */
    protected $identity = 'Workflow Engine';

    public function __construct()
    {
        $this->logger = new NullLogger();
    }

    /**
     * Set the abort flag, if none is provided a SimpleFlag will be created
     *
     * @param FlagInterface $flag
     */
    protected function setAbortFlag(FlagInterface $flag = null)
    {
        if (!$flag) {
            $flag = new SimpleFlag();
        }

        $this->abort_flag = $flag;
    }

    /**
     * Get the abort flag
     *
     * @return FlagInterface
     */
    public function getAbortFlag()
    {
        return $this->abort_flag;
    }

    /**
     * Get Identity
     *
     * @return string
     */
    public function getIdentity()
    {
        return $this->identity;
    }

    /**
     * Set Identity
     *
     * @param string $identity
     * @return void
     */
    public function setIdentity($identity)
    {
        $this->identity = $identity;
    }

    /**
     * Enter a loop, endlessly checking for a decision
     *
     * @param string        $task_list  Optionally override the workflows default tasklist
     * @param FlagInterface $abort_flag A flag used to break the daemon execution
     * @return void
     */
    public function daemonise($task_list = null, FlagInterface $abort_flag = null)
    {
        $this->setAbortFlag($abort_flag);

        do {
            // Dispatch the polling event, allowing other logic to abort the process
            $this->dispatch(Event::DAEMON_POLLING, new PollingEvent($this->getAbortFlag()));

            // Flag has been raised, abort
            if ($this->getAbortFlag()->isRaised()) {
                break;
            }

            // Poll for a task
            $this->checkForTask($task_list);
        } while (true);
    }

    abstract public function checkForTask($task_list = null);

    /**
     * Create a context for logging, containing event details
     *
     * @param WorkflowEvent $event
     * @return array
     */
    protected function createEventContext(WorkflowEvent $event)
    {
        return [
            'workflow_name'    => $event->getWorkflowName(),
            'workflow_version' => $event->getWorkflowVersion(),
            'event_id'         => $event->getEventId(),
            'execution_id'     => $event->getExecutionId(),
            'run_id'           => $event->getRunId(),
            'token'            => $event->getToken(),
        ];
    }
}
