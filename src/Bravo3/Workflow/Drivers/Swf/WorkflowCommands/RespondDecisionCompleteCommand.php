<?php
namespace Bravo3\Workflow\Drivers\Swf\WorkflowCommands;

/**
 * Params:
 *  'decision' => (Decision) The Decision object
 */
class RespondDecisionCompleteCommand extends AbstractWorkflowCommand
{
    use DecisionTrait;

    /**
     * Respond to a decision task and flag the workflow as complete
     *
     * @return void
     */
    public function execute()
    {
        $this->swf->respondDecisionTaskCompleted($this->createArgs());
    }

    /**
     * Create API call arguments
     *
     * @return array
     */
    protected function createArgs()
    {
        $args = [
            'taskToken' => $this->getDecision()->getDecisionToken(),
            'decisions' => $this->buildDecisions(),
        ];

        return $args;
    }

    /**
     * Build response decisions
     *
     * @return array
     */
    protected function buildDecisions()
    {
        $decisions = [
            [
                'decisionType'                                => 'CompleteWorkflowExecution',
                'completeWorkflowExecutionDecisionAttributes' => [
                    'result' => $this->getDecision()->getResult(),
                ]
            ]
        ];

        return $decisions;
    }
}
