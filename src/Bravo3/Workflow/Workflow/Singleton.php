<?php
namespace Bravo3\Workflow\Workflow;

class Singleton
{
    private static $uniqueInstance = null;

    protected function __construct()
    {
    }

    final private function __clone()
    {
    }

    /**
     * Get singleton instance
     *
     * @return $this
     */
    public static function getInstance()
    {
        if (self::$uniqueInstance === null) {
            self::$uniqueInstance = new static();
        }

        return self::$uniqueInstance;
    }
}
