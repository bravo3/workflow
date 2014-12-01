<?php
namespace Bravo3\Workflow\Events;

use Bravo3\Workflow\Task\TaskSchema;
use Symfony\Component\EventDispatcher\Event;

class SchedulingTaskEvent extends Event
{
    /**
     * @var TaskSchema
     */
    protected $task_schema;

    public function __construct(TaskSchema $task_schema)
    {
        $this->task_schema = $task_schema;
    }

    /**
     * Get the task schema
     *
     * @return TaskSchema
     */
    public function getTaskSchema()
    {
        return $this->task_schema;
    }
}
