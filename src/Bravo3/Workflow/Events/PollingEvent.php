<?php
namespace Bravo3\Workflow\Events;

use Bravo3\Workflow\Flags\FlagInterface;
use Symfony\Component\EventDispatcher\Event;

/**
 * Occurs between a daemon polling, giving an opportunity to abort
 */
class PollingEvent extends Event
{
    /**
     * @var FlagInterface
     */
    protected $abort_flag;

    public function __construct(FlagInterface $abort_flag)
    {
        $this->abort_flag = $abort_flag;
    }

    /**
     * Abort the next iteration of polling
     */
    public function abort()
    {
        $this->abort_flag->raise();
    }
}
