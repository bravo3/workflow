<?php
namespace Bravo3\Workflow\Tests\Resources\Tasks;

use Bravo3\Workflow\Events\WorkEvent;
use Bravo3\Workflow\Exceptions\TaskFailedException;
use Bravo3\Workflow\Memory\MemoryPoolInterface;
use Bravo3\Workflow\Task\AbstractTask;
use Bravo3\Workflow\Workflow\WorkflowHistory;

class CharlieTask extends AbstractTask
{
    const EXPECTED_RESULT = "bravo's your man";
    const EXPECTED_MEMORY = '2';

    /**
     * Code to be executed by the WORKER when the task is run
     *
     * @param WorkEvent $event
     * @return void
     */
    public function execute(WorkEvent $event)
    {
        $input = json_decode($event->getInput(), true);

        if ($input['bravo-result'] !== self::EXPECTED_RESULT) {
            throw new TaskFailedException(
                "Input ['bravo-result'] is incorrect. Should be: '".self::EXPECTED_RESULT."', is '".
                $input['bravo-result']."'", $event
            );
        }

        if ($input['bravo-memory'] !== self::EXPECTED_MEMORY) {
            throw new TaskFailedException(
                "Input ['bravo-memory'] is incorrect. Should be: '".self::EXPECTED_MEMORY."', is '".
                $input['bravo-memory']."'", $event
            );
        }

        $this->memory_pool->set('charlie', 3);
        $this->memory_pool->set('state', 'COMPLETE');
        $event->setResult("charlie wins!");
    }

    /**
     * Input factory for the charlie task
     *
     * @param string              $input
     * @param WorkflowHistory     $history
     * @param MemoryPoolInterface $memory_pool
     * @return string
     */
    public static function buildInput($input, WorkflowHistory $history, MemoryPoolInterface $memory_pool)
    {
        $out = [
            'bravo-memory' => $memory_pool->get('bravo'),
            'bravo-result' => $input,
        ];

        return json_encode($out);
    }
}
