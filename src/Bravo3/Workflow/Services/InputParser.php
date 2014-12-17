<?php
namespace Bravo3\Workflow\Services;

use Bravo3\Workflow\Memory\MemoryPoolInterface;
use Bravo3\Workflow\Task\TaskSchema;
use Bravo3\Workflow\Workflow\WorkflowHistory;
use Bravo3\Workflow\Workflow\WorkflowHistoryItem;

class InputParser
{
    const PREFIX_VARIABLE = '$';
    const PREFIX_RESULT   = '@';
    const PREFIX_STRING   = '!';

    /**
     * @var WorkflowHistory
     */
    protected $history;

    /**
     * @var MemoryPoolInterface
     */
    protected $memory_pool;

    public function __construct(WorkflowHistory $history, MemoryPoolInterface $memory_pool)
    {
        $this->history     = $history;
        $this->memory_pool = $memory_pool;
    }

    /**
     * Returns a cloned task with compiled input data
     *
     * @param TaskSchema $task
     * @return TaskSchema
     */
    public function compileTaskInput(TaskSchema $task)
    {
        $compiled = clone $task;
        $input    = $task->getInput();

        // Parse special input types
        if (strlen($input) > 1) {
            $prefix = $input{0};

            switch ($prefix) {
                case self::PREFIX_VARIABLE:
                    $compiled->setInput($this->parseVariable(substr($input, 1)));
                    break;
                case self::PREFIX_RESULT:
                    $compiled->setInput($this->parseResult(substr($input, 1)));
                    break;
                case self::PREFIX_STRING:
                    $compiled->setInput(substr($input, 1));
                    break;
                default:
                    break;
            }
        }

        // Pass-through the input factory
        if ($task->getInputFactory()) {
            $compiled->setInput(
                call_user_func_array(
                    $task->getInputFactory(),
                    [$compiled->getInput(), $this->history, $this->memory_pool]
                )
            );
        }

        return $compiled;
    }

    /**
     * Parse the input and return the value of a variable from the memory pool
     *
     * @param string $in
     * @return string
     */
    protected function parseVariable($in)
    {
        return $this->memory_pool->get($in);
    }

    /**
     * Parse the input and return a task result from the history
     *
     * If the history has multiple occurrences of the same task, the last result will be used.
     *
     * @param string $in
     * @return string
     */
    protected function parseResult($in)
    {
        $result = '';

        foreach ($this->history as $history_item) {
            /** @var WorkflowHistoryItem $history_item */

            echo "*** Test '".$history_item->getActivityKey()."' against '".$in."'\n";

            if ($history_item->getActivityKey() == $in) {
                $result = $history_item->getResult();
                echo "*** Found: ".$result."\n";
            }
        }

        return $result;
    }
}
