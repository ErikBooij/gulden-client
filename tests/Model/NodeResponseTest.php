<?php

namespace GuldenPHP\Tests\Model;

use GuldenPHP\Model\NodeException;
use GuldenPHP\Model\NodeResponse;
use GuldenPHP\Tests\ClientTestHelper;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

/**
 * @covers \GuldenPHP\Model\NodeResponse
 */
class NodeResponseTests extends ClientTestHelper
{
    public function testCreateHydratesResponseFromPsrResponse()
    {
        $response = $this->buildPsrResponse(200, 'awesome job');

        $nodeResponse = NodeResponse::fromPsrResponse($response);

        self::assertEquals(true, $nodeResponse->isSuccessful());
        self::assertEquals('awesome job', $nodeResponse->getBody());
        self::assertEquals(200, $nodeResponse->getStatusCode());
        self::assertEquals('', $nodeResponse->getError());
        self::assertSame($response, $nodeResponse->getOriginalResponse());
    }

    public function testIsSuccessfulReturnsFalseIfAServerErrorOccurred()
    {
        $response = $this->buildPsrResponse(200, [], 'this is an error');

        $nodeResponse = NodeResponse::fromPsrResponse($response);

        self::assertEquals(false, $nodeResponse->isSuccessful());
        self::assertEquals('this is an error', $nodeResponse->getError());
    }

    public function testCreateIgnoresInvalidJSON()
    {
        $responseBody = self::prophesize(StreamInterface::class);
        $responseBody->getContents()->willReturn('{"key": "value"');

        $response = self::prophesize(ResponseInterface::class);
        $response->getStatusCode()->willReturn(200);
        $response->getBody()->willReturn($responseBody);

        $nodeResponse = NodeResponse::fromPsrResponse($response->reveal());

        self::assertEquals(true, $nodeResponse->isSuccessful());
        self::assertEquals('default', $nodeResponse->getBody('default'));
    }

    public function testThrowIfUnsuccessfulThrowsExceptionIfNotSuccessful()
    {
        self::expectException(NodeException::class);

        $responseBody = self::prophesize(StreamInterface::class);
        $responseBody->getContents()->willReturn('');

        $response = self::prophesize(ResponseInterface::class);
        $response->getStatusCode()->willReturn(400);
        $response->getBody()->willReturn($responseBody);

        $nodeResponse = NodeResponse::fromPsrResponse($response->reveal());

        $nodeResponse->throwIfUnsuccessful();
    }

    public function testThrowIfUnsuccessfulReturnsItselfIfSuccessful()
    {
        $responseBody = self::prophesize(StreamInterface::class);
        $responseBody->getContents()->willReturn('');

        $response = self::prophesize(ResponseInterface::class);
        $response->getStatusCode()->willReturn(200);
        $response->getBody()->willReturn($responseBody);

        $nodeResponse = NodeResponse::fromPsrResponse($response->reveal());

        self::assertSame($nodeResponse, $nodeResponse->throwIfUnsuccessful());
    }
}
