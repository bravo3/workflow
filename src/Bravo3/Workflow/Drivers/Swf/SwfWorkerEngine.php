<?php
namespace Bravo3\Workflow\Drivers\Swf;

use Bravo3\Workflow\Drivers\WorkerEngineInterface;
use Bravo3\Workflow\Enum\Event;
use Bravo3\Workflow\Events\WorkEvent;
use Guzzle\Service\Resource\Model;

class SwfWorkerEngine extends SwfEngine implements WorkerEngineInterface
{
    /**
     * Check for a decision task
     *
     * @param string $task_list
     * @return void
     */
    public function checkForTask($task_list = null)
    {
        if (!$task_list) {
            $task_list = $this->getWorkflow()->getTasklist();
        }

        $task = $this->swf->pollForActivityTask(
            [
                'domain'   => $this->getWorkflow()->getDomain(),
                'taskList' => [
                    'name' => $task_list,
                ],
                'identity' => $this->getIdentity(),
            ]
        );

        if ($task->get('startedEventId')) {
            $this->processWorkTask($task);
        }
    }

    /**
     * Parses a Guzzle model returned from SWF and fires the task ready event
     *
     * @param Model $model
     */
    protected function processWorkTask(Model $model)
    {
        if ($model->get('startedEventId') == 0) {
            return;
        }

        $event = new WorkEvent();
        $this->hydrateWorkflowEvent($event, $model);
        $event->setActivityId($model->get('activityId'));
        $event->setInput($model->get('input'));
        $event->setActivityName($model->get('activityType')['name']);
        $event->setActivityVersion($model->get('activityType')['version']);

        $context = $this->createEventContext($event);

        $context['activity_name']    = $event->getActivityName();
        $context['activity_version'] = $event->getActivityVersion();
        $context['activity_id']      = $event->getActivityId();

        $this->logger->info(
            'Found work task for "'.$event->getWorkflowName().'/'.$event->getActivityId()."'",
            $context
        );
        $this->dispatch(Event::TASK_WORK_READY, $event);
    }
}
