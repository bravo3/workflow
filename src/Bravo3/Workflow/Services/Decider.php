<?php
namespace Bravo3\Workflow\Services;

use Bravo3\Workflow\Events\DecisionEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * A service that handles workflow decisions
 */
class Decider extends WorkflowService implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return ['decision_task' => 'processDecisionEvent'];
    }

    public function processDecisionEvent(DecisionEvent $event)
    {
        $tasks = $this->getSchemaProperty('tasks', null, true);

        foreach ($tasks as $task => $parameters) {

        }
    }
}
