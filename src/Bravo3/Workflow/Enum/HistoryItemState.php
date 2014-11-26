<?php
namespace Bravo3\Workflow\Enum;

use Eloquent\Enumeration\AbstractEnumeration;

/**
 * @method static HistoryItemState SCHEDULED()
 * @method static HistoryItemState RUNNING()
 * @method static HistoryItemState COMPLETED()
 * @method static HistoryItemState FAILED()
 * @method static HistoryItemState CANCELLED()
 * @method static HistoryItemState TIMED_OUT()
 */
final class HistoryItemState extends AbstractEnumeration
{
    const SCHEDULED = 'SCHEDULED';
    const RUNNING   = 'RUNNING';
    const COMPLETED = 'COMPLETED';
    const FAILED    = 'FAILED';
    const CANCELLED = 'CANCELLED';
    const TIMED_OUT = 'TIMED_OUT';
}
