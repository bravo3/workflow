<?php
namespace Bravo3\Workflow\Enum;

use Eloquent\Enumeration\AbstractEnumeration;

final class Event extends AbstractEnumeration
{
    const DAEMON_POLLING      = 'daemon.polling';       // A daemon is polling for work
    const TASK_WORK_READY     = 'task.work.ready';      // Work task is ready
    const TASK_DECISION_READY = 'task.decision.ready';  // Decision task is ready
    const DECISION_SCHEDULE   = 'decision.schedule';    // A decider is scheduling a task
    const DECISION_COMPLETE   = 'decision.complete';    // A decider is completing a workflow
    const DECISION_FAIL       = 'decision.fail';        // A decider is failing a workflow
}
