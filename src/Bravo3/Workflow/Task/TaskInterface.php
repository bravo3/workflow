<?php
namespace Bravo3\Workflow\Task;

use Bravo3\Workflow\Worker\WorkerParameterInterface;

interface TaskInterface
{
    public function __construct();

    /**
     * Code to be executed before scheduling the task
     *
     * @return void
     */
    public function pre();

    /**
     * Code executed on workflow success
     *
     * @return void
     */
    public function succeed();

    /**
     * Code executed on workflow failure
     *
     * @return void
     */
    public function failed();

    /**
     * Code executed when a workflow task completes, regardless of success
     *
     * @return void
     */
    public function done();

    /**
     * Set the input for the task
     *
     * @param InputParameterInterface $input
     * @return void
     */
    public function setInput(InputParameterInterface $input);

    /**
     * Get the parameters that are required by the workflow controller to create a new task
     *
     * @return WorkerParameterInterface
     */
    public function getWorkerParameters();
}
