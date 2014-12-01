<?php
namespace Bravo3\Workflow\Drivers;

use Bravo3\Workflow\Workflow\Decision;

interface DecisionEngineInterface extends EngineInterface
{
    /**
     * Process a workflow decision
     *
     * @param Decision $decision
     * @return void
     */
    public function processDecision(Decision $decision);
}
