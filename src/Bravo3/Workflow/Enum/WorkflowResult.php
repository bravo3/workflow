<?php
namespace Bravo3\Workflow\Enum;

use Eloquent\Enumeration\AbstractEnumeration;

/**
 * @method static WorkflowResult COMPLETE()
 * @method static WorkflowResult FAIL()
 * @method static WorkflowResult COMMAND()
 */
final class WorkflowResult extends AbstractEnumeration
{
    const COMPLETE = 'COMPLETE';
    const FAIL     = 'FAIL';
    const COMMAND  = 'COMMAND';
}
