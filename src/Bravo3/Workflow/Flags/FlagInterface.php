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
     * You may have custom logic here, such as checking a filesystem or cache key, but if raise() has been called this
     * function must return true unless lower() has been subsequently called.
     *
     * @return bool
     */
    public function isRaised();
}
