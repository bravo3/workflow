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
     * @return void
     */
    public function checkForTask()
    {
        $task = $this->swf->pollForActivityTask(
            [
                'domain'   => $this->getConfig('domain', null, true),
                'taskList' => [
                    'name' => $this->getConfig('tasklist', null, true),
                ],
                'identity' => $this->getConfig('identity', static::DEFAULT_IDENTITY, false),
            ]
        );

        if ($task) {
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

        $this->logger->info('Found work task for "'.$event->getActivityId()."'", $this->createEventContext($event));
        $this->dispatch(Event::TASK_WORK_READY, $event);
    }
}
