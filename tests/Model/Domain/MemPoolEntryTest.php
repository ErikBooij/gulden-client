<?php

namespace GuldenPHP\Tests\Model\Domain;

use GuldenPHP\Model\Domain\MemPoolEntry;
use GuldenPHP\Tests\Fixtures\Fixtures;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

/**
 * @covers \GuldenPHP\Model\Domain\MemPoolEntry
 */
class MemPoolEntryTest extends TestCase
{
    /**
     * Constructor is disabled for domain models, since they're always instantiated from
     * their respective factory methods and this way they won't bother users of this package
     * with useless code completion suggestions when instantiating new objects.
     */
    public function testConstructorIsDisabled()
    {
        $class = new ReflectionClass(MemPoolEntry::class);

        self::assertFalse($class->getConstructor()->isPublic());
    }

    public function testFromArrayProperlyHydratesInstance()
    {
        $data = [
            'height' => 123456,
            'size' => 123,
            'fee' => 123.45,
            'modifiedfee' => 123.78,
            'time' => 12345678,
            'startingpriority' => 1234.12,
            'currentpriority' => 1234.23,
            'descendantcount' => 1,
            'descendantsize' => 1234.4567,
            'descendantfees' => 12345.3456,
            'ancestorcount' => 123456,
            'ancestorsize' => 123.345678,
            'ancestorfees' => 123.678,
            'depends' => [
                Fixtures::randomHash(),
                Fixtures::randomHash()
            ]
        ];

        $memPoolEntry = MemPoolEntry::fromArray($data);

        self::assertSame($data['height'], $memPoolEntry->getHeight());
        self::assertSame($data['size'], $memPoolEntry->getSize());
        self::assertSame($data['fee'], $memPoolEntry->getFee());
        self::assertSame($data['modifiedfee'], $memPoolEntry->getModifiedFee());
        self::assertSame($data['startingpriority'], $memPoolEntry->getStartingPriority());
        self::assertSame($data['currentpriority'], $memPoolEntry->getCurrentPriority());
        self::assertSame($data['descendantcount'], $memPoolEntry->getDescendantCount());
        self::assertSame($data['descendantsize'], $memPoolEntry->getDescendantSize());
        self::assertSame($data['descendantfees'], $memPoolEntry->getDescendantFees());
        self::assertSame($data['ancestorcount'], $memPoolEntry->getAncestorCount());
        self::assertSame($data['ancestorsize'], $memPoolEntry->getAncestorSize());
        self::assertSame($data['ancestorfees'], $memPoolEntry->getAncestorFees());
        self::assertSame($data['depends'], $memPoolEntry->getDepends());

        self::assertSame($data['time'], (int)$memPoolEntry->getTime()->format('U'));
    }

    public function testFromArrayIgnoresMissingKeys()
    {
        self::assertInstanceOf(MemPoolEntry::class, MemPoolEntry::fromArray([]));
    }
}
