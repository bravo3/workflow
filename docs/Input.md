Task Input
==========
A task input is a string, however if you use special prefixes you can offer other information.

### Variables
Prefixing with a $ will allow you to reference a memory pool variable:

    input: $variablename
    
### Task Results
Prefixing with a @ will allow you to reference the result from another task:

    input: @alpha/1

### Escaped string
Prefixing with a ! will assume that everything that follows is a normal string:

    input: !$somestring
    # $somestring
    
    input: !somestring
    # somestring
    
    input: !!somestring
    # !somestring


Input Factories
===============
Input factories allow you to create a function that will prepare the input for a task, this is useful for gathering
more information from a database or workflow state and serialising the input. The value of the `input` field is passed
to the factory, and the result of the factory is passed to the task.

    input: $somevariable
    input_factory: Path\To\FactoryClass::inputFactory
    
The input factory should look something like

    class FactoryClass
    {
        public static function inputFactory($input)
        {
            return json_encode($input);
        }
    }

