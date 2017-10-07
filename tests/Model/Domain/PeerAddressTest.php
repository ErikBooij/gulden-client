<?php

namespace GuldenPHP\Tests\Model\Domain;

use GuldenPHP\Model\Domain\PeerAddress;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

/**
 * @covers \GuldenPHP\Model\Domain\PeerAddress
 */
class PeerAddressTest extends TestCase
{
    /**
     * Constructor is disabled for domain models, since they're always instantiated from
     * their respective factory methods and this way they won't bother users of this package
     * with useless code completion suggestions when instantiating new objects.
     */
    public function testConstructorIsDisabled()
    {
        $class = new ReflectionClass(PeerAddress::class);

        self::assertFalse($class->getConstructor()->isPublic());
    }

    public function testFromStringShouldCreateProperInstance()
    {
        $address = PeerAddress::fromString('1.2.3.4:9231');

        self::assertEquals('1.2.3.4', $address->getAddress());
        self::assertEquals(9231, $address->getPort());
    }

    public function testFromStringShouldSetPortToZeroIfNotProvided()
    {
        $address = PeerAddress::fromString('1.2.3.4');

        self::assertEquals(0, $address->getPort());
    }

    public function testPeerAddressShouldBeCastableToString()
    {
        $address = PeerAddress::fromString('1.2.3.4:1234');

        self::assertEquals('1.2.3.4:1234', (string)$address);
    }
}
