<?php
namespace Bravo3\Workflow\Tests\Resources\Workflow;

use Bravo3\Workflow\Events\CompletingWorkflowEvent;
use Bravo3\Workflow\Events\FailingWorkflowEvent;
use Bravo3\Workflow\Events\WorkflowAwareEvent;
use Bravo3\Workflow\Memory\MemoryPoolInterface;
use Bravo3\Workflow\Workflow\AbstractWorkflowCallback;
use Bravo3\Workflow\Workflow\WorkflowHistory;

class Callbacks extends AbstractWorkflowCallback
{
    /**
     * Called when the workflow completes successfully
     *
     * @param CompletingWorkflowEvent $event
     * @return void
     */
    public static function onWorkflowSuccess(CompletingWorkflowEvent $event)
    {
        $logger = self::getInstance()->getLogger();
        $logger->debug("Workflow success: ".$event->getResult());
    }

    /**
     * Called when the workflow fails
     *
     * @param FailingWorkflowEvent $event
     * @return void
     */
    public static function onWorkflowFail(FailingWorkflowEvent $event)
    {
        $logger = self::getInstance()->getLogger();
        $logger->debug("Workflow failed: ".$event->getReason());
    }

    /**
     * Called when the workflow completes, regardless of success or failure
     *
     * @param WorkflowAwareEvent $event
     * @return void
     */
    public static function onWorkflowComplete(WorkflowAwareEvent $event)
    {
        $memory_pool = self::getInstance()->getMemoryPoolForWorkflow($event->getWorkflow(), $event->getExecutionId());
        $logger = self::getInstance()->getLogger();
        $logger->debug("Workflow complete");
        $logger->debug("State flag: ".$memory_pool->get('state'));
        $memory_pool->set("complete", '1');
    }
}
