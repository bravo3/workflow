<?php
namespace Bravo3\Workflow\Drivers\Swf\HistoryCommands;

use Bravo3\Workflow\Exceptions\InsufficientDataException;
use Bravo3\Workflow\Exceptions\MalformedHistoryException;
use Bravo3\Workflow\Exceptions\OutOfBoundsException;
use Bravo3\Workflow\Workflow\WorkflowHistory;
use Bravo3\Workflow\Workflow\WorkflowHistoryItem;

abstract class AbstractHistoryCommand implements HistoryCommandInterface
{
    /**
     * @var \DateTime
     */
    protected $timestamp;

    /**
     * @var array
     */
    protected $attributes;

    /**
     * @var string
     */
    protected $event_id;

    public function __construct(\DateTime $timestamp, array $attributes, $event_id)
    {
        $this->timestamp  = $timestamp;
        $this->attributes = $attributes;
        $this->event_id   = $event_id;
    }

    /**
     * Get an attribute or default value, optionally raising an exception if the key does not exist
     *
     * You can get nested array values by providing an array of the array path as the key.
     *
     * @param string|array $key
     * @param string       $default
     * @param bool         $raise_exception
     * @return string
     */
    protected function getAttribute($key, $default = '', $raise_exception = false)
    {
        if (!is_array($key)) {
            $key = [$key];
        }

        $value = $this->attributes;
        foreach ($key as $item) {
            if (!isset($value[$item])) {
                if ($raise_exception) {
                    throw new InsufficientDataException("Workflow attribute '".$item."' not found");
                }

                return $default;
            }

            $value = $value[$item];
        }

        return $value;
    }

    /**
     * Get a history item, raising an an exception if it does not exist
     *
     * @param WorkflowHistory $history
     * @param string          $event_id
     * @return WorkflowHistoryItem
     */
    protected function getHistoryItem(WorkflowHistory $history, $event_id)
    {
        try {
            return $history[$event_id];
        } catch (OutOfBoundsException $e) {
            throw new MalformedHistoryException($history, $event_id, $e);
        }
    }
}
