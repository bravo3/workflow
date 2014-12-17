<?php
namespace Bravo3\Workflow\Events;

use Bravo3\Workflow\Workflow\WorkflowSchema;
use Symfony\Component\EventDispatcher\Event;

class WorkflowSchemaEvent extends Event
{
    /**
     * @var WorkflowSchema
     */
    protected $schema;

    public function __construct(WorkflowSchema $schema)
    {
        $this->schema = $schema;
    }

    /**
     * Get Schema
     *
     * @return WorkflowSchema
     */
    public function getSchema()
    {
        return $this->schema;
    }
}
