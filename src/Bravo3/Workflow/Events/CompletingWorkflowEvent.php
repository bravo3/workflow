<?php
namespace Bravo3\Workflow\Events;

use Bravo3\Workflow\Task\TaskSchema;
use Bravo3\Workflow\Workflow\WorkflowInterface;
use Symfony\Component\EventDispatcher\Event;

class CompletingWorkflowEvent extends Event
{
    /**
     * @var WorkflowInterface
     */
    protected $workflow;

    public function __construct(WorkflowInterface $workflow)
    {
        $this->workflow = $workflow;
    }

    /**
     * Get Workflow
     *
     * @return WorkflowInterface
     */
    public function getWorkflow()
    {
        return $this->workflow;
    }
}
