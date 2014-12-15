<?php
namespace Bravo3\Workflow\Drivers\Swf\HistoryCommands;

use Bravo3\Workflow\Workflow\WorkflowHistory;

interface HistoryCommandInterface
{
    /**
     * @param \DateTime $timestamp
     * @param array     $attributes
     * @param string    $event_id
     */
    public function __construct(\DateTime $timestamp, array $attributes, $event_id);

    public function apply(WorkflowHistory $history);
} 