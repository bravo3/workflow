Workflow Engine
===============
The purpose of this library is to create a decision engine to augment workflow services such as Amazon SWF.
 
The decision engine allows you to create a simple schema for the workflow and minimise programmatic business logic or
exposure of decision and workflow handling.

Example
=======
To quickly create a schema-based workflow using SWF, you can use factories provided and follow this example:
  
    TBA

Application Structure
=====================
The lowest level component is a decision or worker engine found in the Drivers namespace. Once an engine is created,
you must add the appropriate Decider or Worker service (Services namespace) as a subscriber.
 
The engine classes will fire events when they receive decision or work tasks. The services will then action the task
using a Workflow and WorkflowHistory entity.
 
Tasks (Tasks namespace) are worker components of a workflow. A task not only contains all information for scheduling 
worker tasks in the workflow engine, but it allows for pre and post-execution code blocks which allow you to prepare or 
handle the input/output of a worker task. The tasks `execute()` function contains the logic that should be run by the
worker itself.

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

