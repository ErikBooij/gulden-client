<?php

namespace GuldenPHP\Tests\Model\Domain;

use GuldenPHP\Model\Domain\MemPool;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

/**
 * @covers \GuldenPHP\Model\Domain\MemPool
 */
class MemPoolTest extends TestCase
{
    /**
     * Constructor is disabled for domain models, since they're always instantiated from
     * their respective factory methods and this way they won't bother users of this package
     * with useless code completion suggestions when instantiating new objects.
     */
    public function testConstructorIsDisabled()
    {
        $class = new ReflectionClass(MemPool::class);

        self::assertFalse($class->getConstructor()->isPublic());
    }

    public function testFromArrayProperlyHydratesInstance()
    {
        $data = [
            'bytes' => 1234,
            'maxmempool' => 12345,
            'mempoolminfee' => 123.0,
            'size' => 12,
            'usage' =>  1234
        ];

        $memPool = MemPool::fromArray($data);

        self::assertSame($data['bytes'], $memPool->getBytes());
        self::assertSame($data['maxmempool'], $memPool->getMaxMemPoolSize());
        self::assertSame($data['mempoolminfee'], $memPool->getMinFee());
        self::assertSame($data['size'], $memPool->getSize());
        self::assertSame($data['usage'], $memPool->getUsage());
    }

    public function testFromArrayIgnoresMissingKeys()
    {
        self::assertInstanceOf(MemPool::class, MemPool::fromArray([]));
    }
}
