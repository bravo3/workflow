<?php
namespace Bravo3\Workflow\Workflow;

use Bravo3\Workflow\Exceptions\InsufficientDataException;
use Bravo3\Workflow\Exceptions\NotReadableException;
use Bravo3\Workflow\Task\TaskSchema;
use Symfony\Component\Yaml\Yaml;

/**
 * A workflow built from a YAML schema
 */
class YamlWorkflow implements WorkflowInterface
{
    /**
     * @var array
     */
    protected $schema;

    public function __construct($schema_filename)
    {
        if (!is_readable($schema_filename)) {
            throw new NotReadableException($schema_filename);
        }

        $this->schema = Yaml::parse($schema_filename);
    }

    /**
     * Get a schema property, delimited by periods
     *
     * @param string $path
     * @param mixed  $default
     * @param bool   $mandatory
     * @return mixed
     */
    protected function getSchemaProperty($path, $default = null, $mandatory = false)
    {
        $keys   = explode('.', $path);
        $schema = $this->schema;

        foreach ($keys as $key) {
            if (!array_key_exists($key, $schema)) {
                if ($mandatory) {
                    throw new InsufficientDataException("Schema property '".$path."' does not exist");
                } else {
                    return $default;
                }
            }

            $schema = $schema[$key];
        }

        return $schema;
    }

    /**
     * Get all task schemas in the workflow
     *
     * @return TaskSchema[]
     */
    public function getTasks()
    {
        $schemas = $this->getSchemaProperty('tasks', null, true);
        $tasks = [];

        foreach ($schemas as $task_name => $schema) {
            $tasks[] = TaskSchema::fromArray($schema);
        }

        return $tasks;
    }

    /**
     * Get the domain that the workflow resides in
     *
     * @return string
     */
    public function getDomain()
    {
        return $this->getSchemaProperty('workflow.domain', null, true);
    }

    /**
     * Get the decision tasklist
     *
     * @return string
     */
    public function getTasklist()
    {
        return $this->getSchemaProperty('workflow.tasklist');
    }

    /**
     * Start to close timeout of the entire workflow in seconds
     *
     * @return int
     */
    public function getStartToCloseTimeout()
    {
        return $this->getSchemaProperty('workflow.start_to_close_timeout');
    }

    /**
     * True if the memory pool should be jailed to the current execution, if false, all executions share the same
     * memory pool namespace
     *
     * @return bool
     */
    public function getJailMemoryPool()
    {
        return $this->getSchemaProperty('workflow.jail_memory_pool', true);
    }

    /**
     * Get the workflow name
     *
     * @return string
     */
    public function getWorkflowName()
    {
        return $this->getSchemaProperty('workflow.workflow_name', null, true);
    }

    /**
     * Get the workflow version
     *
     * @return string
     */
    public function getWorkflowVersion()
    {
        return $this->getSchemaProperty('workflow.workflow_version', null, true);
    }
}
