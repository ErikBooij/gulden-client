<?php

namespace GuldenPHP\Tests\Model;

use GuldenPHP\Model\InputException;
use PHPUnit\Framework\TestCase;

/**
 * @covers \GuldenPHP\Model\InputException
 */
class InputExceptionTest extends TestCase
{
    public function testInputExceptionHasFunctioningFactoryMethod()
    {
        $exception = InputException::withMessage('exception message');

        self::assertInstanceOf(InputException::class, $exception);
        self::assertEquals('exception message', $exception->getMessage());
    }
}
