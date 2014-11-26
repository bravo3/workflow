<?php
namespace Bravo3\Workflow\Enum;

use Eloquent\Enumeration\AbstractEnumeration;

final class Event extends AbstractEnumeration
{
    const TASK_WORK_READY     = 'task.work.ready';
    const TASK_DECISION_READY = 'task.decision.ready';
}
