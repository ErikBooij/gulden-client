<?php

namespace GuldenPHP\Tests;

use GuldenPHP\Model\NodeException;
use GuldenPHP\Model\NodeResponse;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class ClientTestHelper extends TestCase
{
    /**
     * @param int         $statusCode
     * @param mixed       $body
     * @param string|null $errorMessage
     *
     * @return object
     */
    protected function buildPsrResponse(int $statusCode, $body, string $errorMessage = null)
    {
        $body = ['result' => $body];

        if (!is_null($errorMessage)) {
            $body['error'] = [
                'message' => $errorMessage
            ];
        }

        $responseBody = self::prophesize(StreamInterface::class);
        $responseBody->getContents()->willReturn(json_encode($body));

        $response = self::prophesize(ResponseInterface::class);
        $response->getStatusCode()->willReturn($statusCode);
        $response->getBody()->willReturn($responseBody);

        return $response->reveal();
    }

    /**
     * @param int    $statusCode
     * @param mixed  $body
     * @param string $errorMessage
     *
     * @return object
     */
    protected function buildNodeResponse(int $statusCode, $body = '', string $errorMessage = '')
    {
        $isSuccessful = $errorMessage === '' && $statusCode === NodeResponse::STATUS_SUCCESS;

        $psrResponse = $this->buildPsrResponse($statusCode, $body, $errorMessage);

        $nodeResponse = self::prophesize(NodeResponse::class);
        $nodeResponse->getOriginalResponse()->willReturn($psrResponse);
        $nodeResponse->getStatusCode()->willReturn($statusCode);
        $nodeResponse->getBody()->willReturn($body);
        $nodeResponse->getError()->willReturn($errorMessage);
        $nodeResponse->isSuccessful()->willReturn($isSuccessful);

        if (!$isSuccessful) {
            $nodeResponse->throwIfUnsuccessful()->willThrow(NodeException::withMessage($errorMessage));
        } else {
            $nodeResponse->throwIfUnsuccessful()->willReturn($nodeResponse);
        }

        return $nodeResponse->reveal();
    }

    /**
     * @param $captured
     *
     * @return Argument\Token\CallbackToken
     */
    protected function captureArgument(&$captured)
    {
        return Argument::that(function ($argument) use (&$captured): bool {
            $captured = $argument;

            return true;
        });
    }
}
