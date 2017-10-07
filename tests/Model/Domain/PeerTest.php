<?php

namespace GuldenPHP\Tests\Model\Domain;

use DateTime;
use GuldenPHP\Model\Domain\Peer;
use GuldenPHP\Model\Domain\PeerAddress;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

/**
 * @covers \GuldenPHP\Model\Domain\Peer
 */
class PeerTest extends TestCase
{
    /**
     * Constructor is disabled for domain models, since they're always instantiated from
     * their respective factory methods and this way they won't bother users of this package
     * with useless code completion suggestions when instantiating new objects.
     */
    public function testConstructorIsDisabled()
    {
        $class = new ReflectionClass(Peer::class);

        self::assertFalse($class->getConstructor()->isPublic());
    }

    public function testFromArrayProperlyHydratesInstance()
    {
        $peer = Peer::fromArray([
            'addr'       => '1.2.3.4:1234',
            'addrlocal'  => '2.3.4.5:2345',
            'inbound'    => true,
            'version'    => 70014,
            'subver'     => '/1.6.4.1/',
            'conntime'   => 12345678,
            'timeoffset' => 0,
            'relaytxes'  => true,
            'pingtime'   => 0.002,
            'minping'    => 0.0017,
        ]);

        self::assertInstanceOf(Peer::class, $peer);
        self::assertInstanceOf(PeerAddress::class, $peer->getRemoteAddress());
        self::assertEquals('1.2.3.4:1234', (string)$peer->getRemoteAddress());
        self::assertInstanceOf(PeerAddress::class, $peer->getLocalAddress());
        self::assertEquals('2.3.4.5:2345', (string)$peer->getLocalAddress());
        self::assertEquals(true, $peer->isInbound());
        self::assertEquals(70014, $peer->getVersion());
        self::assertEquals('1.6.4.1', $peer->getSubVersion());
        self::assertInstanceOf(DateTime::class, $peer->getConnectionTime());
        self::assertEquals(12345678, $peer->getConnectionTime()->getTimestamp());
        self::assertEquals(0, $peer->getTimeOffset());
        self::assertEquals(true, $peer->getRelayTransactions());
        self::assertEquals(0.002, $peer->getPingTime());
        self::assertEquals(0.0017, $peer->getMinimumPingTime());
    }

    public function testFromArrayIgnoresMissingKeys()
    {
        self::assertInstanceOf(Peer::class, Peer::fromArray([]));
    }
}
