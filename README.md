Workflow Engine
===============
The purpose of this library is to create a decision engine to augment workflow services such as Amazon SWF.
 
The decision engine allows you to create a simple schema for the workflow and minimise programmatic business logic or
exposure of decision and workflow handling.

Example
=======
To quickly create a schema-based workflow using SWF (see Building a Workflow), you can use factories provided:
  
    $factory = new SchemaWorkflowFactory(
        'Path/To/Schema.yml',
        $aws_config,    // AWS config
        $redis_config,  // Redis config
        3600,           // Redis key timeout - should be longer than the workflow start-to-close timeout
        'workflows'     // Redis namespace - used to isolate workflow activity on the server
    );
    
This factory has all your workflow components will need. To create a new workflow: 

    $factory->getWorkflowEngine()->createWorkflow('sample-workflow-name');
    
On the daemon side, to listen for decisions:

    $factory->getDecisionEngine()->daemonise();

And to listen for work tasks:

    $factory->getWorkerEngine()->daemonise();
    
The `#daemonise()` functions run in a loop endlessly. To abort this loop you can pass it an abort flag 
(`FlagInterface`), using a `MemoryFlag` would allow you to abort on a condition in the workflow.

If you don't want to loop, you could just call `#checkForTask()`:

    $factory->getWorkerEngine()->checkForTask();

Ad-Hoc Scheduling
-----------------
It's possible for a task to schedule additional tasks upon success (or manipulate the workflow in any way). This
nature is controlled at the DECISION level, so the task must first succeed and then it's `onSuccess()` function will
be executed by the decider. You will receive a `Decision` object which you can then use to add decisions for the
decider to execute (fail execution, schedule task, etc) and use the result from the `execute()` function of your task
that was executed by the worker.

Passing Data To The Worker Tasks
--------------------------------
The Worker and Decider classes have auxiliary data that can be set by calling `setAuxPayload()`. If you are using a
factory, the `$payload` parameter of the factory will set the aux payload on any Worker/Decider classes it creates.
 
In a task you can reference this data (`getAuxPayload()` if using the AbstractTask) - this is useful for giving the
task access to a larger application engine. A useful payload might be a DI container.

Application Structure
=====================
The lowest level component is a decision or worker engine found in the Drivers namespace. Once an engine is created,
you must add the appropriate Decider or Worker service (Services namespace) as a subscriber.
 
The engine classes will fire events when they receive decision or work tasks. The services will then action the task
using a Workflow and WorkflowHistory entity. You can add multiple decision and worker services, each with their own
logic. The bundled classes will follow the task requirements and close the workflow when there are no more tasks running
or to be scheduled, but it is possible to completely rewrite this logic.
 
Tasks are worker components of a workflow - these are executed by the Worker service and run independent of the 
Decision service.

Memory services are interfaces to a database platform which is used to store metadata created by worker tasks.

Memory Pools
============
This workflow engine uses key-value memory pools to store state and ephemeral workflow information. It is the 
responsibility of the Task classes to persist data as required.

[Redis](docs/Redis.md) is an ideal memory pool.

Building a Workflow
===================
To run a workflow you require a workflow controller, a SaaS service like Amazon SWF that is your workflow engine
controlling which activities need to be executed, and querying decision by talking to this decision interface.

In the workflow controller, you will need to define a domain, a simple name that defines your workflow environment -
you may with to use a different domain for testing and production. Inside that domain, you need to define a workflow
execution. This contains a default tasklist, this tasklist is your "decision tasklist", and must be defined in your
workflow schema.

Defining Tasks
--------------
In your workflow controller you must also define activities as steps in a workflow execution. An activity requires an 
`activity name` and an `activity version`. You can assign default timeout values at the SWF (or equiv provider), 
although you may also override these at the schema level.

