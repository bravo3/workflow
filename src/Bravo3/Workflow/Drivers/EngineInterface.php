<?php
namespace Bravo3\Workflow\Drivers;

use Bravo3\Workflow\Flags\FlagInterface;
use Bravo3\Workflow\Workflow\WorkflowAwareInterface;
use Psr\Log\LoggerAwareInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

interface EngineInterface extends EventDispatcherInterface, LoggerAwareInterface, WorkflowAwareInterface
{
    /**
     * Get the abort flag used to break daemon execution
     *
     * @return FlagInterface
     */
    public function getAbortFlag();

    /**
     * Check for a single task, firing an event if found
     *
     * @param string $task_list Optionally override the workflows default tasklist
     * @return void
     */
    public function checkForTask($task_list = null);

    /**
     * Enter a loop, endlessly checking for a decision
     *
     * @param string        $task_list  Optionally override the workflows default tasklist
     * @param FlagInterface $abort_flag A flag used to break the daemon execution
     * @return void
     */
    public function daemonise($task_list = null, FlagInterface $abort_flag = null);

    /**
     * Get the engines identity
     *
     * @return string
     */
    public function getIdentity();

    /**
     * Set the engines identity
     *
     * @param string $identity
     * @return void
     */
    public function setIdentity($identity);
}
