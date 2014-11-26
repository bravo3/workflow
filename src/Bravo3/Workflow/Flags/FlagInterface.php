<?php
namespace Bravo3\Workflow\Flags;

/**
 * A flag, used to indicate a required intercept or break
 */
interface FlagInterface
{
    /**
     * Raise the flag
     *
     * @return void
     */
    public function raise();

    /**
     * Lower the flag
     *
     * @return void
     */
    public function lower();

    /**
     * Check if the flag has been raised
     *
     * @return bool
     */
    public function isRaised();
}
