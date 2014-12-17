<?php
namespace Bravo3\Workflow\Factories;

use Bravo3\Workflow\Drivers\Swf\SwfDecisionEngine;
use Bravo3\Workflow\Drivers\Swf\SwfWorkerEngine;
use Bravo3\Workflow\Drivers\Swf\SwfWorkflowEngine;
use Bravo3\Workflow\Memory\MemoryPoolInterface;
use Bravo3\Workflow\Memory\RedisMemoryPool;
use Bravo3\Workflow\Services\Decider;
use Bravo3\Workflow\Services\Worker;
use Bravo3\Workflow\Workflow\WorkflowInterface;
use Bravo3\Workflow\Workflow\YamlWorkflow;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

/**
 * Quick set-up factory for standard components
 */
class SchemaWorkflowFactory
{
    /**
     * @var MemoryPoolInterface
     */
    protected $memory_pool;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var WorkflowInterface
     */
    protected $workflow;

    /**
     * @var Decider
     */
    protected $decider;

    /**
     * @var Worker
     */
    protected $worker;

    /**
     * @var SwfDecisionEngine
     */
    protected $decision_engine;

    /**
     * @var SwfWorkerEngine
     */
    protected $worker_engine;

    /**
     * @var SwfWorkflowEngine
     */
    protected $workflow_engine;

    /**
     * @var array
     */
    protected $swf;

    /**
     * Build all required workflow components using:
     * - Workflow Controller: Amazon SWF
     * - Workflow Class:      YamlWorkflow
     * - Memory Pool:         Redis
     * - Decision Engine:     Bundled
     * - Worker Engine:       Bundled
     *
     * Adding a logger is optional.
     *
     * @param string $schema_fn Filename of the YAML schema
     * @param array  $swf       SWF parameters
     * @param array  $redis     Redis parameters
     * @param int    $ttl       Redis key TTL
     * @param string $namespace Redis key namespace
     */
    public function __construct($schema_fn, array $swf, array $redis, $ttl = 3600, $namespace = null)
    {
        $this->workflow    = new YamlWorkflow($schema_fn);
        $this->memory_pool = new RedisMemoryPool($namespace, $ttl, $redis);
        $this->logger      = new NullLogger();
        $this->swf         = $swf;
    }

    /**
     * Build all decider components
     */
    protected function buildDecider()
    {
        $this->decider = new Decider();
        $this->decider->setWorkflow($this->workflow);
        $this->decider->setMemoryPool($this->memory_pool);

        $this->decision_engine = new SwfDecisionEngine($this->swf);
        $this->decision_engine->setWorkflow($this->workflow);
        $this->decision_engine->addSubscriber($this->decider);
        $this->decision_engine->setLogger($this->logger);
    }

    /**
     * Build all worker components
     */
    protected function buildWorker()
    {
        $this->worker = new Worker();
        $this->worker->setWorkflow($this->workflow);
        $this->worker->setMemoryPool($this->memory_pool);

        $this->worker_engine = new SwfWorkerEngine($this->swf);
        $this->worker_engine->setWorkflow($this->workflow);
        $this->worker_engine->addSubscriber($this->worker);
        $this->worker_engine->setLogger($this->logger);
    }

    /**
     * Build the workflow engine
     */
    protected function buildWorkflowEngine()
    {
        $this->workflow_engine = new SwfWorkflowEngine($this->swf);
        $this->workflow_engine->setWorkflow($this->workflow);
        $this->workflow_engine->setLogger($this->logger);
    }

    /**
     * Get the logger
     *
     * @return LoggerInterface
     */
    public function getLogger()
    {
        return $this->logger;
    }

    /**
     * Set the logger and apply to all components
     *
     * @param LoggerInterface $logger
     * @return $this
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;

        if ($this->decision_engine) {
            $this->decision_engine->setLogger($this->logger);
        }

        if ($this->worker_engine) {
            $this->worker_engine->setLogger($this->logger);
        }

        if ($this->workflow_engine) {
            $this->workflow_engine->setLogger($this->logger);
        }

        return $this;
    }

    /**
     * Get Decider
     *
     * @return Decider
     */
    public function getDecider()
    {
        if (!$this->decider) {
            $this->buildDecider();
        }

        return $this->decider;
    }

    /**
     * Get DecisionEngine
     *
     * @return SwfDecisionEngine
     */
    public function getDecisionEngine()
    {
        if (!$this->decision_engine) {
            $this->buildDecider();
        }

        return $this->decision_engine;
    }

    /**
     * Get MemoryPool
     *
     * @return MemoryPoolInterface
     */
    public function getMemoryPool()
    {
        return $this->memory_pool;
    }

    /**
     * Get Worker
     *
     * @return Worker
     */
    public function getWorker()
    {
        if (!$this->worker) {
            $this->buildWorker();
        }

        return $this->worker;
    }

    /**
     * Get WorkerEngine
     *
     * @return SwfWorkerEngine
     */
    public function getWorkerEngine()
    {
        if (!$this->worker_engine) {
            $this->buildWorker();
        }

        return $this->worker_engine;
    }

    /**
     * Get Workflow
     *
     * @return WorkflowInterface
     */
    public function getWorkflow()
    {
        return $this->workflow;
    }

    /**
     * Get WorkflowEngine
     *
     * @return SwfWorkflowEngine
     */
    public function getWorkflowEngine()
    {
        if (!$this->workflow_engine) {
            $this->buildWorkflowEngine();
        }

        return $this->workflow_engine;
    }
}
