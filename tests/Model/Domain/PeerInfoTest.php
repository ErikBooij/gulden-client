<?php

namespace GuldenPHP\Tests\Model\Domain;

use GuldenPHP\Model\Domain\PeerInfo;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

/**
 * @covers \GuldenPHP\Model\Domain\PeerInfo
 */
class PeerInfoTest extends TestCase
{
    /**
     * Constructor is disabled for domain models, since they're always instantiated from
     * their respective factory methods and this way they won't bother users of this package
     * with useless code completion suggestions when instantiating new objects.
     */
    public function testConstructorIsDisabled()
    {
        $class = new ReflectionClass(PeerInfo::class);

        self::assertFalse($class->getConstructor()->isPublic());
    }

    public function testGetPeersShouldReturnPeersSortedByConnectionTime()
    {
        $peers = [
            $this->createPeer(2345, false),
            $this->createPeer(1234, true),
            $this->createPeer(3456, true),
        ];

        $peerInfo = PeerInfo::fromArray($peers);

        self::assertInstanceOf(PeerInfo::class, $peerInfo);

        self::assertEquals(3, $peerInfo->numberOfPeers());
        self::assertEquals(2, $peerInfo->numberOfInboundPeers());
        self::assertEquals(1, $peerInfo->numberOfOutboundPeers());

        // Check distinction between in- and outbound peers, as wel as sorting
        self::assertEquals(1234, $peerInfo->getPeers()[0]->getConnectionTime()->getTimestamp());
        self::assertEquals(2345, $peerInfo->getPeers()[1]->getConnectionTime()->getTimestamp());
        self::assertEquals(3456, $peerInfo->getPeers()[2]->getConnectionTime()->getTimestamp());
        self::assertSame(2345, $peerInfo->getOutboundPeers()[0]->getConnectionTime()->getTimestamp());
        self::assertSame(1234, $peerInfo->getInboundPeers()[0]->getConnectionTime()->getTimestamp());
        self::assertSame(3456, $peerInfo->getInboundPeers()[1]->getConnectionTime()->getTimestamp());
    }

    private function createPeer(int $connectionTime, bool $inbound): array
    {
        return [
            'conntime' => $connectionTime,
            'inbound'  => $inbound,
        ];
    }
}
