Amazon Simple Workflow (SWF)
============================

Create a new domain for tests (any name), keep this specific to a user to avoid conflicts.

* Inside that domain, create a `Workflow Type` named 'test' with version '1':
    * Task List: test-decision
    * Execution Start To Close Timeout: 1 minute
    * Task Start To Close Timeout: 15 seconds
    * Child Policy: Terminate
* Create an `Activity Type` named 'test-activity' with version '1':
    * Task List: test-activity
    * Task Schedule to Close Timeout: Not Specified
    * Task Schedule to Start Timeout: 1 minute
    * Task Start to Close Timeout: 15 seconds
    * Task Heartbeat Timeout: Not Specified
