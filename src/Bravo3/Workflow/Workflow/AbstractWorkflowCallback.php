<?php
namespace Bravo3\Workflow\Workflow;

use Bravo3\Workflow\Events\CompletingWorkflowEvent;
use Bravo3\Workflow\Events\FailingWorkflowEvent;
use Bravo3\Workflow\Events\WorkflowAwareEvent;
use Bravo3\Workflow\Memory\JailedMemoryPool;
use Bravo3\Workflow\Memory\MemoryPoolInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

abstract class AbstractWorkflowCallback extends Singleton implements LoggerAwareInterface
{
    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var MemoryPoolInterface
     */
    protected $memory_pool;

    protected function __construct()
    {
        $this->logger = new NullLogger();
    }

    /**
     * Called when the workflow completes successfully
     *
     * @param CompletingWorkflowEvent $event
     * @return void
     */
    public static function onWorkflowSuccess(CompletingWorkflowEvent $event)
    {
    }


    /**
     * Called when the workflow fails
     *
     * @param FailingWorkflowEvent $event
     * @return void
     */
    public static function onWorkflowFailed(FailingWorkflowEvent $event)
    {
    }

    /**
     * Called when the workflow completes, regardless of success or failure
     *
     * @param WorkflowAwareEvent $event
     * @return void
     */
    public static function onWorkflowComplete(WorkflowAwareEvent $event)
    {
    }

    /**
     * Sets a logger instance on the object
     *
     * @param LoggerInterface $logger
     * @return $this
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
        return $this;
    }

    /**
     * Get the logger
     *
     * @return LoggerInterface
     */
    protected function getLogger()
    {
        return $this->logger;
    }

    /**
     * Get the memory pool
     *
     * @return MemoryPoolInterface
     */
    protected function getMemoryPool()
    {
        return $this->memory_pool;
    }

    /**
     * Get a (jailed) memory pool matching this workflow execution
     *
     * @param WorkflowInterface $workflow
     * @param string            $execution_id
     * @return MemoryPoolInterface
     */
    protected function getMemoryPoolForWorkflow(WorkflowInterface $workflow, $execution_id)
    {
        return $workflow->getJailMemoryPool() ?
            JailedMemoryPool::jail($this->getMemoryPool(), ':'.$execution_id) :
            $this->getMemoryPool();
    }

    /**
     * Set the memory pool
     *
     * @param MemoryPoolInterface $memory_pool
     * @return $this
     */
    public function setMemoryPool($memory_pool)
    {
        $this->memory_pool = $memory_pool;
        return $this;
    }
}
