<?php
namespace Bravo3\Workflow\Exceptions;

class NotReadableException extends \RuntimeException implements WorkflowException
{
    protected $resource_name;

    public function __construct($resource_name, \Exception $previous = null)
    {
        $this->resource_name = $resource_name;
        parent::__construct("Resource is not readable: ".basename($resource_name), 0, $previous);
    }

    /**
     * Get the file or resource name that is not readable
     *
     * @return string
     */
    public function getResourceName()
    {
        return $this->resource_name;
    }
}
