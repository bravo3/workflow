<?php
namespace Bravo3\Workflow\Services;

use Bravo3\Workflow\Memory\MemoryPoolAwareInterface;
use Bravo3\Workflow\Memory\MemoryPoolAwareTrait;
use Bravo3\Workflow\Workflow\WorkflowAwareInterface;
use Bravo3\Workflow\Workflow\WorkflowAwareTrait;

class WorkflowService implements WorkflowAwareInterface, MemoryPoolAwareInterface
{
    use WorkflowAwareTrait;
    use MemoryPoolAwareTrait;
}
