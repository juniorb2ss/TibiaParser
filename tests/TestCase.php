<?php
namespace juniorb2ss\TibiaParser;

use Mockery;

class TestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * Get protected method reflection
     * @param ClassName $class Class to reflection operations
     * @param array $constructArgs Args to class construct
     * @param string $name Method name
     *
     * @return object method visible instance
     */
    protected static function getProtectedMethod($class, $constructArgs, $name)
    {
        $class = new \ReflectionClass($class);
        $method = $class->getMethod($name);
        $method->setAccessible(true);

        return $method;
    }
}
