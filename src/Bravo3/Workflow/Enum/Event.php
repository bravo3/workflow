<?php
namespace Bravo3\Workflow\Enum;

use Eloquent\Enumeration\AbstractEnumeration;

final class Event extends AbstractEnumeration
{
    const DAEMON_POLLING      = 'daemon.polling';
    const TASK_WORK_READY     = 'task.work.ready';
    const TASK_DECISION_READY = 'task.decision.ready';
}
