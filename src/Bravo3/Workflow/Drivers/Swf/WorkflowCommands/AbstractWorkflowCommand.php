<?php
namespace Bravo3\Workflow\Drivers\Swf\WorkflowCommands;

use Aws\Swf\SwfClient;
use Bravo3\Workflow\Exceptions\InsufficientDataException;
use Bravo3\Workflow\Workflow\WorkflowInterface;

abstract class AbstractWorkflowCommand implements WorkflowCommandInterface
{
    /**
     * @var SwfClient
     */
    protected $swf;

    /**
     * @var WorkflowInterface
     */
    protected $workflow;

    /**
     * @var array
     */
    protected $params;

    public function __construct(SwfClient $swf, WorkflowInterface $workflow, array $params = null)
    {
        $this->swf      = $swf;
        $this->workflow = $workflow;
        $this->params   = $params;
    }

    /**
     * Get a parameter
     *
     * @param string $key
     * @param mixed  $default
     * @return mixed
     */
    protected function getParameter($key, $default = null, $mandatory = false)
    {
        if (array_key_exists($key, $this->params)) {
            return $this->params[$key];
        } else {
            if ($mandatory) {
                throw new InsufficientDataException("Mandatory key missing: ".$key);
            } else {
                return $default;
            }
        }
    }
}
