Workflow Engine
===============
The purpose of this library is to create a decision engine to augment workflow services such as Amazon SWF.
 
The decision engine allows you to create a simple schema for the workflow and minimise programmatic business logic or
exposure of decision and workflow handling.

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
