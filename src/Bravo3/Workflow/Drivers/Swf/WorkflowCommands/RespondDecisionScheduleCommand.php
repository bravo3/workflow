<?php
namespace Bravo3\Workflow\Drivers\Swf\WorkflowCommands;

/**
 * Params:
 *  'decision' => (Decision) The Decision object
 */
class RespondDecisionScheduleCommand extends AbstractWorkflowCommand
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
        $decisions = [];

        $i = 0;
        foreach ($this->getDecision()->getScheduledTasks() as $task) {
            $tasklist = $task->getTasklist() ?: $this->workflow->getTasklist();

            $attribs = [
                'activityType' => [
                    'name'    => $task->getActivityName(),
                    'version' => $task->getActivityVersion(),
                ],
                'activityId'   => $task->getActivityName().'.'.$task->getActivityVersion().'.'.$i++.'.'.time(),
                'control'      => $task->getControl(),
                'input'        => $task->getInput(),
                'taskList'     => [
                    'name' => $tasklist,
                ]
            ];

            if ($task->getScheduleToCloseTimeout()) {
                $attribs['scheduleToCloseTimeout'] = $task->getScheduleToCloseTimeout();
            }

            if ($task->getScheduleToStartTimeout()) {
                $attribs['scheduleToStartTimeout'] = $task->getScheduleToStartTimeout();
            }

            if ($task->getStartToCloseTimeout()) {
                $attribs['startToCloseTimeout'] = $task->getStartToCloseTimeout();
            }

            if ($task->getHeartbeatTimeout()) {
                $attribs['heartbeatTimeout'] = $task->getHeartbeatTimeout();
            }

            $decisions[] = [
                'decisionType'                           => 'ScheduleActivityTask',
                'scheduleActivityTaskDecisionAttributes' => $attribs,
            ];
        }

        return $decisions;
    }
}
