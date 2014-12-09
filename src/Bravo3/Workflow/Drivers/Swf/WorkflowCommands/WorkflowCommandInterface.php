<?php
namespace Bravo3\Workflow\Drivers\Swf\WorkflowCommands;

use Aws\Swf\SwfClient;
use Bravo3\Workflow\Workflow\WorkflowInterface;

interface WorkflowCommandInterface
{
    /**
     * @param SwfClient         $swf
     * @param WorkflowInterface $workflow
     * @param array             $params
     */
    public function __construct(SwfClient $swf, WorkflowInterface $workflow, array $params = null);

    /**
     * Execute the command
     *
     * @return mixed
     */
    public function execute();
}
