<?php

namespace GuldenPHP\Tests\Model;

use GuldenPHP\Model\NodeException;
use PHPUnit\Framework\TestCase;

/**
 * @covers \GuldenPHP\Model\NodeException
 */
class NodeExceptionTest extends TestCase
{
    public function testNodeExceptionHasFunctioningFactoryMethod()
    {
        $exception = NodeException::withMessage('exception message');

        self::assertInstanceOf(NodeException::class, $exception);
        self::assertEquals('exception message', $exception->getMessage());
    }
}
