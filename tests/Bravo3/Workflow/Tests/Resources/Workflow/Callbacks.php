<?php
namespace Bravo3\Workflow\Tests\Resources\Workflow;

use Bravo3\Workflow\Events\CompletingWorkflowEvent;
use Bravo3\Workflow\Events\FailingWorkflowEvent;
use Bravo3\Workflow\Events\WorkflowAwareEvent;
use Bravo3\Workflow\Workflow\AbstractWorkflowCallback;

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
    public static function onWorkflowFailed(FailingWorkflowEvent $event)
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
        $logger = self::getInstance()->getLogger();
        $logger->debug("Workflow complete");
        $logger->debug(
            "State flag: ".self::getInstance()->
            getMemoryPoolForWorkflow($event->getWorkflow(), $event->getExecutionId())->
            get('state')
        );
    }
}
