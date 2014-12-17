Unit Tests
----------

The unit tests themselves, including the integration group will not actually make external calls. As such, their code
coverage is very limited do to the lack of mocks. Run the live integration tests (below) to do live testing against a
workflow controller.

The integration group (`phpunit --group integration`) will run tests that have small delays. By default, these tests
are excluded.


Amazon Simple Workflow (SWF) Integration Tests
----------------------------------------------

### Setup

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

### Running

There are some PHP scripts in the root of the tests/ folder, to run a mock workflow, run both the `tests/decider` and
`tests/worker` scripts at the same time. You might want to start the worker first to avoid the aggressive timeouts on
the test workflow. This will run a workflow built from a YamlSchema in the tests Resources directory.

In addition to the above, you can also use the `tests/factory-decider` and `tests/factory-worker` scripts. These
achieve the same except they use the `SchemaWorkflowFactory` class to build all services instead of manually creating
them. This creates a much cleaner looking daemon.

### Termination Test

TBA

