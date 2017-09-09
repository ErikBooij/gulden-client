<?php

namespace GuldenPHP\Tests\Model\Domain;

use GuldenPHP\Model\Domain\ChainTip;
use GuldenPHP\Tests\Fixtures\Fixtures;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

/**
 * @covers \GuldenPHP\Model\Domain\ChainTip
 */
class ChainTipTest extends TestCase
{
    /**
     * Constructor is disabled for domain models, since they're always instantiated from
     * their respective factory methods and this way they won't bother users of this package
     * with useless code completion suggestions when instantiating new objects.
     */
    public function testConstructorIsDisabled()
    {
        $class = new ReflectionClass(ChainTip::class);

        self::assertFalse($class->getConstructor()->isPublic());
    }

    public function testFromArrayProperlyHydratesInstance()
    {
        $data = [
            'height' => 123546,
            'branchlen' => 1,
            'hash' => Fixtures::randomHash(),
            'status' => ''
        ];

        $chainTip = ChainTip::fromArray($data);

        self::assertSame($data['height'], $chainTip->getHeight());
        self::assertSame($data['branchlen'], $chainTip->getBranchLength());
        self::assertSame($data['hash'], $chainTip->getHash());
        self::assertSame($data['status'], $chainTip->getStatus());
    }

    public function testFromArrayIgnoresMissingKeys()
    {
        self::assertInstanceOf(ChainTip::class, ChainTip::fromArray([]));
    }
}
