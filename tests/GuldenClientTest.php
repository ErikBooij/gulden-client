<?php

namespace GuldenPHP\Tests;

use GuldenPHP\GuldenClient;
use GuldenPHP\Model\NodeResponse;
use GuldenPHP\SubClient\AccountsSubClient;
use GuldenPHP\SubClient\BlockChainSubClient;
use GuldenPHP\SubClient\ControlSubClient;
use GuldenPHP\SubClient\NetworkSubClient;
use GuldenPHP\SubClient\WalletSubClient;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;
use Psr\Http\Message\RequestInterface;

/**
 * @covers \GuldenPHP\GuldenClient
 */
class GuldenClientTest extends ClientTestHelper
{
    /** @var GuldenClient */
    private $guldenClient;

    /** @var ObjectProphecy|Client */
    private $httpClient;

    public function setUp()
    {
        $this->httpClient = self::prophesize(Client::class);

        $this->guldenClient = new GuldenClient('user', 'pass', '127.0.0.1', 100000, $this->httpClient->reveal());
    }

    public function testCreateSetsDefaultsForProtocolAndPortIfNotProperlySupplied()
    {
        $this->httpClient->send($this->captureArgument($request))
            ->willReturn($this->buildPsrResponse(NodeResponse::STATUS_SUCCESS, []));

        $this->guldenClient->verifyConnection();

        /** @var RequestInterface $request */
        self::assertRegExp('/^http\:\/\/.*/', (string)$request->getUri());
        self::assertEquals(9232, $request->getUri()->getPort());
    }

    public function testVerifyConnectionWillReturnTrueIfConnectionSucceeds()
    {
        $this->httpClient->send(Argument::type(RequestInterface::class))
            ->willReturn($this->buildPsrResponse(NodeResponse::STATUS_SUCCESS, []));

        self::assertTrue($this->guldenClient->verifyConnection());
    }

    public function testVerifyConnectionWillReturnFalseIfConnectionDoesNotSucceed()
    {
        $this->httpClient->send(Argument::type(RequestInterface::class))
            ->willReturn($this->buildPsrResponse(401, ''));

        self::assertFalse($this->guldenClient->verifyConnection());
    }

    /**
     * @param string $address
     * @param bool   $validity
     * @param string $testCase
     *
     * @testWith ["G123456789abcdefghijkmnopqrstuvwxyz",                true,   "valid address with uppercase 'G'"]
     *           ["g123456789abcdefghijkmnopqrstuvwxyz",                true,   "valid address with lowercase 'G'"]
     *           ["G123456789abcdefghijklmnopqrstuvwxyz",               false,  "address containing a lowercase 'L'"]
     *           ["G123456789abcdefghIjkmnopqrstuvwxyz",                false,  "address containing a uppercase 'I'"]
     *           ["G123456789abcdefghijkmnOpqrstuvwxyz",                false,  "address containing a uppercase 'O'"]
     *           ["G123456789abcdefghijkmn0pqrstuvwxyz",                false,  "address containing the number '0'"]
     *           ["G123456789abcdefghijkmnopqrstuvwxyzABCDEFGHJKLMNP",  false,  "too long an address"]
     *           ["G123456789abcdefghijkmnop",                          false,  "too short short an address"]
     */
    public function testValidateAddressFormatReturnsProperValidityOfAddressFormat(
        string $address,
        bool $validity,
        string $testCase
    ) {
        self::assertEquals($validity, $this->guldenClient->validateAddressFormat($address), $testCase);
    }

    /**
     * @param string $address
     * @param bool   $validity
     * @param string $testCase
     *
     * @testWith ["Ga1GEKzjGdXBs43R8JsWP7V8kQUR2nV1NH",  true,  "valid address"]
     *           ["Ga1GEKzjGdXBs43R8JsWP7V8kQUR2nV1NI",  false, "last letter of address changed"]
     */
    public function testValidateAddressVerifiesAddressChecksum(
        string $address,
        bool $validity,
        string $testCase
    ) {
        self::assertEquals($validity, $this->guldenClient->validateAddress($address), $testCase);
    }

    public function testUnknownMethodsAreRoutedToTheServerEndpoint()
    {
        $randomMethodName = str_replace(range(0, 9), range('a', 'j'), bin2hex(random_bytes(8)));

        $this->httpClient
            ->send($this->captureArgument($request))
            ->willReturn(
                $this->buildPsrResponse(NodeResponse::STATUS_SUCCESS, '')
            );

        call_user_func([$this->guldenClient, $randomMethodName], 'some-parameter', 'second-parameter');

        $body = json_decode($request->getBody()->getContents(), true);

        self::assertArrayHasKey('method', $body);
        self::assertEquals($randomMethodName, $body['method']);
        self::assertArrayHasKey('params', $body);
        self::assertEquals(['some-parameter', 'second-parameter'], $body['params']);
    }

    public function testSubClientsAreAvailableTroughTheirRespectiveNames()
    {
        self::assertInstanceOf(AccountsSubClient::class, $this->guldenClient->accounts());
        self::assertInstanceOf(BlockChainSubClient::class, $this->guldenClient->blockChain());
        self::assertInstanceOf(ControlSubClient::class, $this->guldenClient->control());
        self::assertInstanceOf(NetworkSubClient::class, $this->guldenClient->network());
        self::assertInstanceOf(WalletSubClient::class, $this->guldenClient->wallet());
    }

    public function testClientExceptionsAreCaughtAndConvertedToErroredNodeResponses()
    {
        $request = self::prophesize(RequestInterface::class);
        $response = $this->buildPsrResponse(400, [], 'the request was invalid');

        $this->httpClient->send(Argument::any())->willThrow(
            new ClientException(
                'request failed',
                $request->reveal(),
                $response
            )
        );

        $status = $this->guldenClient->executeCommand('anything');

        self::assertFalse($status->isSuccessful());
    }

    public function testServerExceptionsAreCaughtAndConvertedToErroredNodeResponses()
    {
        $request = self::prophesize(RequestInterface::class);
        $response = $this->buildPsrResponse(400, [], 'the request was invalid');

        $this->httpClient->send(Argument::any())->willThrow(
            new ServerException(
                'request failed',
                $request->reveal(),
                $response
            )
        );

        $status = $this->guldenClient->executeCommand('anything');

        self::assertFalse($status->isSuccessful());
    }

    public function testHelpReturnsInformationAboutMethod()
    {
        $method = 'getinfo';
        $explanation = 'explanation';

        $response = $this->buildPsrResponse(200, $explanation);

        $this->httpClient->send($this->captureArgument($request))->willReturn($response);

        $this->guldenClient->help($method);

        self::assertSame($explanation, $this->guldenClient->help($method));

        $body = json_decode($request->getBody()->getContents(), true);

        self::assertSame('help', $body['method']);
        self::assertContains($method, $body['params']);
    }
}
