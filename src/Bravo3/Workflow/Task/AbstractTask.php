<?php
namespace Bravo3\Workflow\Task;

abstract class AbstractTask
{
    /**
     * @var InputParameterInterface
     */
    protected $input;

    /**
     * Code to be executed before scheduling the task
     *
     * @return void
     */
    public function pre()
    {
    }

    /**
     * Code executed on workflow success
     *
     * @return void
     */
    public function succeed()
    {
    }

    /**
     * Code executed on workflow failure
     *
     * @return void
     */
    public function failed()
    {
    }

    /**
     * Code executed when a workflow task completes, regardless of success
     *
     * @return void
     */
    public function done()
    {
    }

    /**
     * Set the input for the task
     *
     * @param InputParameterInterface $input
     * @return void
     */
    public function setInput(InputParameterInterface $input)
    {
        $this->input = $input;
    }
}
