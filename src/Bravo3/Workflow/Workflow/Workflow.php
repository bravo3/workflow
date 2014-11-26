<?php
namespace Bravo3\Workflow\Workflow;

/**
 * An entity that represents an actual workflow process
 */
class Workflow
{
    protected $tasks;

    public function __construct(array $tasks)
    {
        $this->tasks = $tasks;
    }

    public static function createFromSchema($filename)
    {

    }

}
