Amazon Simple Workflow (SWF)
============================

Create a new domain for tests (any name), keep this specific to a user to avoid conflicts.

* Inside that domain, create a `Workflow Type` named 'test' with version '1':
    * Task List: test-decision
    * Execution Start To Close Timeout: 1 minute
    * Task Start To Close Timeout: 15 seconds
    * Child Policy: Terminate
* Create an `Activity Type` named 'alpha' with version '1', leave all details blank
* Create an `Activity Type` named 'bravo' with version '1', leave all details blank
* Create an `Activity Type` named 'charlie' with version '1', leave all details blank
* Create an `Activity Type` named 'omega' with version '1', leave all details blank
