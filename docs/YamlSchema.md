The below is an example of the workflow schema with comments to each items function

    # Workflow top-level config
    workflow:
        # Timeout in seconds for the workflow to complete in
        start_to_close_timeout: 60
        # Workflow domain, configured in your workflow controller
        domain: somedomain
        # A default tasklist for the decision engine, you can override this post workflow creation to allow you to run 
        # multiple independent workflows using the same schema 
        tasklist: sometasklist
        # The name of the workflow
        workflow_name: test
        # The version of the workflow
        workflow_version: 1
        # Jailing the memory pool means that each execution has it's own namespace and cannot see other executions
        # memory pools
        jail_memory_pool: true
        # Optional callback functions executed when the workflow completes
        on_success: Bravo3\Workflow\Tests\Resources\Workflow\Callbacks::onWorkflowSuccess
        on_fail: Bravo3\Workflow\Tests\Resources\Workflow\Callbacks::onWorkflowFail
        on_complete: Bravo3\Workflow\Tests\Resources\Workflow\Callbacks::onWorkflowComplete
    
    # A list of tasks, their classes and their conditions
    tasks:
        # Task name and version (name/version) - this must match the name and version defined in your workflow controller
        alpha/1:
            # List of requirements, if left blank, the task will execute as soon as the workflow is created
            requires:
            # Class name for the task - this contains an excute function that will be called to execute the task
            class: Bravo3\Workflow\Tests\Resources\Tasks\AlphaTask
            # Task input, see the Input documentation
            input: xxx
            # Task control, not seen by the task itself, but used to create conditions for other tasks
            control: alpha
            # Task timeouts in seconds
            schedule_to_start_timeout: 5
            schedule_to_close_timeout: 15
            start_to_close_timeout: 10
            heartbeat_timeout: 10
            # Optional: override the tasklist, if left blank, the workflows tasklist will be assumed
            # CAUTION: This should match a workflow tasklist, be sure you understand what you are doing before changing 
            #          a tasks tasklist
            tasklist: sometasklist
            
        charlie/1:
            requires:
                # A list of activities that must succeed before executing this task
                activity:
                    - bravo/1
                # A list of control variables that must have x successful executions before executing this task
                control:
                    bravo: 1
                # A list of (jailed) variables that must match in value before executing this task
                variable:
                    alpha: "1"
                    bravo: "2"
            ...

