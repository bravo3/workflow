<?php
namespace Bravo3\Workflow\Tests\Resources;

use Psr\Log\AbstractLogger;
use Psr\Log\LoggerInterface;

class TestLogger extends AbstractLogger implements LoggerInterface
{
    /**
     * Logs with an arbitrary level.
     *
     * @param mixed  $level
     * @param string $message
     * @param array  $context
     * @return null
     */
    public function log($level, $message, array $context = [])
    {
        echo $message."\n";

        if ($context) {
            foreach ($context as $key => $value) {
                echo " - [".$key."]: ".$value."\n";
            }
        }
    }
}
