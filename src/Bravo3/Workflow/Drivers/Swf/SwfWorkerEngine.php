<?php
namespace Bravo3\Workflow\Drivers\Swf;

use Bravo3\Workflow\Drivers\WorkerEngineInterface;
use Bravo3\Workflow\Enum\Event;
use Bravo3\Workflow\Events\WorkEvent;
use Guzzle\Service\Resource\Model;

class SwfWorkerEngine extends SwfEngine implements WorkerEngineInterface
{
    /**
     * Check for a work task
     *
     * @return void
     */
    public function checkForTask()
    {
        $task = $this->swf->pollForActivityTask(
            [
                'domain'   => $this->getWorkflow()->getDomain(),
                'taskList' => [
                    'name' => $this->getWorkflow()->getTasklist(),
                ],
                'identity' => $this->getIdentity(),
            ]
        );

        if ($task->get('startedEventId')) {
            $token = $task->get('taskToken');

            try {
                $event = $this->processWorkTask($task);
                $this->respondSuccess($token, $event->getResult());
            } catch (\Exception $e) {
                $this->respondFailed($token, $e->getMessage());
            }
        }
    }

    /**
     * Respond to an activity task with success
     *
     * @param string $token
     * @param string $result
     */
    protected function respondSuccess($token, $result)
    {
        $this->swf->respondActivityTaskCompleted(
            [
                'taskToken' => $token,
                'result'    => $result,
            ]
        );
    }

    /**
     * Respond to an activity task with a failure
     *
     * @param string $token
     * @param string $reason
     * @param string $detail
     */
    protected function respondFailed($token, $reason, $detail = null)
    {
        $this->swf->respondActivityTaskFailed(
            [
                'taskToken' => $token,
                'reason'    => $reason,
                'details'   => $detail,
            ]
        );
    }

    /**
     * Parses a Guzzle model returned from SWF and fires the task ready event
     *
     * @param Model $model
     * @return WorkEvent
     */
    protected function processWorkTask(Model $model)
    {
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
            'Found work task for "'.$event->getExecutionId().'/'.$event->getActivityId().'"',
            $context
        );
        $this->dispatch(Event::TASK_WORK_READY, $event);

        return $event;
    }
}
