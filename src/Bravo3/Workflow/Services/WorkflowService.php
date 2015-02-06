<?php
namespace Bravo3\Workflow\Services;

use Bravo3\Workflow\Memory\MemoryPoolAwareInterface;
use Bravo3\Workflow\Memory\MemoryPoolAwareTrait;
use Bravo3\Workflow\Workflow\WorkflowAwareInterface;
use Bravo3\Workflow\Workflow\WorkflowAwareTrait;

class WorkflowService implements WorkflowAwareInterface, MemoryPoolAwareInterface
{
    use WorkflowAwareTrait;
    use MemoryPoolAwareTrait;

    /**
     * @var mixed
     */
    protected $aux_payload;

    /**
     * Get auxiliary payload
     *
     * @return mixed
     */
    public function getAuxPayload()
    {
        return $this->aux_payload;
    }

    /**
     * Set auxiliary payload
     *
     * @param mixed $aux_payload
     * @return $this
     */
    public function setAuxPayload($aux_payload)
    {
        $this->aux_payload = $aux_payload;
        return $this;
    }
}
