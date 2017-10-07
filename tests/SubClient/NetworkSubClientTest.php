<?php

namespace GuldenPHP\Tests\SubClient;

use GuldenPHP\GuldenClient;
use GuldenPHP\Model\Domain\PeerInfo;
use GuldenPHP\Model\NodeException;
use GuldenPHP\Model\NodeResponse;
use GuldenPHP\SubClient\NetworkSubClient;
use GuldenPHP\Tests\ClientTestHelper;
use Prophecy\Prophecy\ObjectProphecy;

/**
 * @covers \GuldenPHP\SubClient\AbstractSubClient
 * @covers \GuldenPHP\SubClient\NetworkSubClient
 */
class NetworkSubClientTest extends ClientTestHelper
{
    /** @var NetworkSubClient*/
    private $client;

    /** @var ObjectProphecy|GuldenClient */
    private $guldenClient;

    public function setUp()
    {
        $this->guldenClient = self::prophesize(GuldenClient::class);

        $this->client = NetworkSubClient::fromClient($this->guldenClient->reveal());
    }

    public function testGetPeerInfoReturnsPeerInfoIfRequestSucceeds()
    {
        $response = $this->buildNodeResponse(NodeResponse::STATUS_SUCCESS, []);

        $this->guldenClient->executeCommand('getpeerinfo')->willReturn($response);

        self::assertInstanceOf(PeerInfo::class, $this->client->getPeerInfo());
    }

    public function testGetPeerInfoThrowsExceptionIfRequestFails()
    {
        self::expectException(NodeException::class);

        $response = $this->buildNodeResponse(400);

        $this->guldenClient->executeCommand('getpeerinfo')->willReturn($response);

        $this->client->getPeerInfo();
    }
}
