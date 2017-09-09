<?php

namespace GuldenPHP\Tests\SubClient;

use GuldenPHP\GuldenClient;
use GuldenPHP\Model\Domain\Node;
use GuldenPHP\Model\NodeException;
use GuldenPHP\Model\NodeResponse;
use GuldenPHP\SubClient\ControlSubClient;
use GuldenPHP\Tests\ClientTestHelper;
use GuldenPHP\Tests\Fixtures\Fixtures;
use Prophecy\Prophecy\ObjectProphecy;

/**
 * @covers \GuldenPHP\SubClient\AbstractSubClient
 * @covers \GuldenPHP\SubClient\ControlSubClient
 */
class ControlSubClientTest extends ClientTestHelper
{
    /** @var ControlSubClient */
    private $client;

    /** @var ObjectProphecy|GuldenClient */
    private $guldenClient;

    public function setUp()
    {
        $this->guldenClient = self::prophesize(GuldenClient::class);

        $this->client = ControlSubClient::fromClient($this->guldenClient->reveal());
    }

    public function testGetInfoReturnsNodeInfoIfRequestSucceeds()
    {
        $response = $this->buildNodeResponse(NodeResponse::STATUS_SUCCESS, Fixtures::nodeInfo());

        $this->guldenClient->executeCommand('getinfo')->willReturn($response);

        self::assertInstanceOf(Node::class, $this->client->getInfo());
    }

    public function testGetInfoThrowsExceptionIfRequestFails()
    {
        self::expectException(NodeException::class);

        $response = $this->buildNodeResponse(400);

        $this->guldenClient->executeCommand('getinfo')->willReturn($response);

        $this->client->getInfo();
    }

    public function testStopDoesNotReturnAnythingIfRequestSucceeds()
    {
        $response = $this->buildNodeResponse(NodeResponse::STATUS_SUCCESS, true);

        $this->guldenClient->executeCommand('stop')->willReturn($response);

        self::assertNull($this->client->stop());
    }

    public function testStopThrowsExceptionIfRequestFails()
    {
        self::expectException(NodeException::class);

        $response = $this->buildNodeResponse(400);

        $this->guldenClient->executeCommand('stop')->willReturn($response);

        $this->client->stop();
    }
}
