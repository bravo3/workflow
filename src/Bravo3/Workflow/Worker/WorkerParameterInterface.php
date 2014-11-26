<?php
namespace Bravo3\Workflow\Worker;

interface WorkerParameterInterface
{
    /**
     * Get the activity name
     *
     * @return string
     */
    public function getActivityName();

    /**
     * Get the activity version
     *
     * @return string
     */
    public function getActivityVersion();

    /**
     * Get the task input, that will be passed to the worker
     *
     * @return string
     */
    public function getInput();

    /**
     * A control variable, only used by the workflow controller
     *
     * @return string
     */
    public function getControl();

    /**
     * Schedule to start timeout in seconds
     *
     * @return int
     */
    public function getScheduleToStartTimeout();

    /**
     * Schedule to close timeout in seconds
     *
     * @return int
     */
    public function getScheduleToCloseTimeout();

    /**
     * Start to close timeout in seconds
     *
     * @return int
     */
    public function getStartToCloseTimeout();

    /**
     * Timeout if a heartbeat has not been received in seconds
     *
     * @return int
     */
    public function getHeartbeatTimeout();
}
