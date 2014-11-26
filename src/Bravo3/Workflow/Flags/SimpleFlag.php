<?php
namespace Bravo3\Workflow\Flags;

/**
 * Simple in-memory flag
 */
class SimpleFlag implements FlagInterface
{
    /**
     * @var bool
     */
    protected $raised;

    /**
     * @param bool $raised
     */
    function __construct($raised = false)
    {
        $this->raised = $raised;
    }

    /**
     * Raise the flag
     *
     * @return void
     */
    public function raise()
    {
        $this->raised = true;
    }

    /**
     * Lower the flag
     *
     * @return void
     */
    public function lower()
    {
        $this->raised = false;
    }

    /**
     * Check if the flag has been raised
     *
     * @return bool
     */
    public function isRaised()
    {
        return $this->raised;
    }
}
