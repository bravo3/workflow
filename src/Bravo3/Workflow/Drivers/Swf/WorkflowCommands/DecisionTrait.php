<?php
namespace Bravo3\Workflow\Drivers\Swf\WorkflowCommands;

use Bravo3\Workflow\Workflow\Decision;
use UnexpectedValueException;

trait DecisionTrait
{
    abstract function getParameter($key, $default = null, $mandatory = false);

    /**
     * Get the workflow decision
     *
     * @return Decision
     */
    protected function getDecision()
    {
        $decision = $this->getParameter('decision', null, true);

        if (!($decision instanceof Decision)) {
            throw new UnexpectedValueException("Decision passed is not a Decision");
        }

        return $decision;
    }
}
