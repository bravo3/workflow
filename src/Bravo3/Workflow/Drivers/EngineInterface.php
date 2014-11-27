<?php
namespace Bravo3\Workflow\Drivers;

use Bravo3\Workflow\Flags\FlagInterface;
use Psr\Log\LoggerAwareInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

interface EngineInterface extends EventDispatcherInterface, LoggerAwareInterface
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
     * @param string $task_list
     * @return void
     */
    public function checkForTask($task_list);

    /**
     * Enter a loop, endlessly checking for a decision
     *
     * @param string $task_list
     * @param FlagInterface $abort_flag A flag used to break the daemon execution
     * @return void
     */
    public function daemonise($task_list, FlagInterface $abort_flag = null);
}
