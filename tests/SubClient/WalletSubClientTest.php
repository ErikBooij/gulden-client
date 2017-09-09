<?php

namespace GuldenPHP\Tests\SubClient;

use GuldenPHP\GuldenClient;
use GuldenPHP\Model\Domain\Wallet;
use GuldenPHP\Model\InputException;
use GuldenPHP\Model\NodeException;
use GuldenPHP\Model\NodeResponse;
use GuldenPHP\SubClient\WalletSubClient;
use GuldenPHP\Tests\ClientTestHelper;
use GuldenPHP\Tests\Fixtures\Fixtures;
use Prophecy\Argument;
use Prophecy\Prophecy\ObjectProphecy;

/**
 * @covers \GuldenPHP\SubClient\AbstractSubClient
 * @covers \GuldenPHP\SubClient\WalletSubClient
 */
class WalletSubClientTest extends ClientTestHelper
{
    /** @var WalletSubClient */
    private $client;

    /** @var ObjectProphecy|GuldenClient */
    private $guldenClient;

    public function setUp()
    {
        $this->guldenClient = self::prophesize(GuldenClient::class);

        $this->client = WalletSubClient::fromClient($this->guldenClient->reveal());
    }

    public function testAbandonTransactionDoesNotReturnAnythingIfRequestSucceeds()
    {
        $transactionId = Fixtures::randomHash();

        $response = $this->buildNodeResponse(NodeResponse::STATUS_SUCCESS);

        $this->guldenClient->executeCommand('abandontransaction', $transactionId)->willReturn($response);

        self::assertNull($this->client->abandonTransaction($transactionId));
    }

    public function testAbandonTransactionThrowsExceptionIfRequestFails()
    {
        self::expectException(NodeException::class);

        $transactionId = Fixtures::randomHash();

        $response = $this->buildNodeResponse(400);

        $this->guldenClient->executeCommand('abandontransaction', $transactionId)->willReturn($response);

        $this->client->abandonTransaction($transactionId);
    }

    public function testBackUpWalletDoesNotReturnAnythingIfRequestSucceeds()
    {
        $filename = 'wallet.dat';

        $response = $this->buildNodeResponse(NodeResponse::STATUS_SUCCESS);

        $this->guldenClient->executeCommand('backupwallet', $filename)->willReturn($response);

        self::assertNull($this->client->backUpWallet($filename));
    }

    public function testBackUpWalletThrowsExceptionIfRequestFails()
    {
        self::expectException(NodeException::class);

        $filename = 'wallet.dat';

        $response = $this->buildNodeResponse(400);

        $this->guldenClient->executeCommand('backupwallet', $filename)->willReturn($response);

        $this->client->backUpWallet($filename);
    }

    public function testGetBalanceReturnsBalanceIfRequestSucceeds()
    {
        $response = $this->buildNodeResponse(NodeResponse::STATUS_SUCCESS, 123.45);

        $this->guldenClient->executeCommand('getbalance', '*', 0, false)->willReturn($response);

        self::assertSame(123.45, $this->client->getBalance());
    }

    public function testGetBalanceThrowsExceptionIfRequestFails()
    {
        self::expectException(NodeException::class);

        $response = $this->buildNodeResponse(400);

        $this->guldenClient->executeCommand('getbalance', '*', 0, false)->willReturn($response);

        $this->client->getBalance();
    }

    public function testGetNewAddressReturnsAddressIfRequestSucceeds()
    {
        $account = 'account-name';
        $address = Fixtures::randomAddress();

        $response = $this->buildNodeResponse(NodeResponse::STATUS_SUCCESS, $address);

        $this->guldenClient->executeCommand('getnewaddress', $account)->willReturn($response);

        self::assertSame($address, $this->client->getNewAddress($account));
    }

    public function testGetNewAddressThrowsExceptionIfRequestFails()
    {
        self::expectException(NodeException::class);

        $account = 'account-name';

        $response = $this->buildNodeResponse(400);

        $this->guldenClient->executeCommand('getnewaddress', $account)->willReturn($response);

        $this->client->getNewAddress($account);
    }

    public function testGetRawChangeAddressReturnsRawChangeAddressIfRequestSucceeds()
    {
        $address = Fixtures::randomAddress();

        $response = $this->buildNodeResponse(NodeResponse::STATUS_SUCCESS, $address);

        $this->guldenClient->executeCommand('getrawchangeaddress')->willReturn($response);

        self::assertSame($address, $this->client->getRawChangeAddress());
    }

    public function testGetRawChangeAddressThrowsExceptionIfRequestFails()
    {
        self::expectException(NodeException::class);

        $response = $this->buildNodeResponse(400);

        $this->guldenClient->executeCommand('getrawchangeaddress')->willReturn($response);

        $this->client->getRawChangeAddress();
    }

    public function testGetUnconfirmedBalanceReturns___IfRequestSucceeds()
    {
        $unconfirmedBalance = 123.45;

        $response = $this->buildNodeResponse(NodeResponse::STATUS_SUCCESS, $unconfirmedBalance);

        $this->guldenClient->executeCommand('getunconfirmedbalance')->willReturn($response);

        self::assertSame($unconfirmedBalance, $this->client->getUnconfirmedBalance());
    }

    public function testGetUnconfirmedBalanceThrowsExceptionIfRequestFails()
    {
        self::expectException(NodeException::class);

        $response = $this->buildNodeResponse(400);

        $this->guldenClient->executeCommand('getunconfirmedbalance')->willReturn($response);

        $this->client->getUnconfirmedBalance();
    }

    public function testGetWalletInfoReturnsWalletIfRequestSucceeds()
    {
        $response = $this->buildNodeResponse(NodeResponse::STATUS_SUCCESS, Fixtures::walletInfo());

        $this->guldenClient->executeCommand('getwalletinfo')->willReturn($response);

        self::assertInstanceOf(Wallet::class, $this->client->getWalletInfo());
    }

    public function testGetWalletInfoThrowsExceptionIfRequestFails()
    {
        self::expectException(NodeException::class);

        $response = $this->buildNodeResponse(400);

        $this->guldenClient->executeCommand('getwalletinfo')->willReturn($response);

        $this->client->getWalletInfo();
    }

    public function testMoveReturnsStateOfMoveIfRequestSucceeds()
    {
        $success = true;

        $fromAccount = 'from-account';
        $toAccount = 'to-account';
        $amount = 123.45;

        $response = $this->buildNodeResponse(NodeResponse::STATUS_SUCCESS, $success);

        $this->guldenClient->executeCommand('move', $fromAccount, $toAccount, $amount, 1, '')->willReturn($response);

        self::assertSame($success, $this->client->move($fromAccount, $toAccount, $amount));
    }

    public function testMoveThrowsExceptionIfInputAmountIsLessThanZero()
    {
        self::expectException(InputException::class);

        $this->client->move('from-account', 'to-account', 0);
    }

    public function testMoveThrowsExceptionIfRequestFails()
    {
        self::expectException(NodeException::class);

        $fromAccount = 'from-account';
        $toAccount = 'to-account';
        $amount = 123.45;

        $response = $this->buildNodeResponse(400);

        $this->guldenClient->executeCommand('move', $fromAccount, $toAccount, $amount, 1, '')->willReturn($response);

        $this->client->move($fromAccount, $toAccount, $amount);
    }

    public function testRefillKeyPoolDoeNotReturnAnythingIfRequestSucceeds()
    {
        $response = $this->buildNodeResponse(NodeResponse::STATUS_SUCCESS);

        $this->guldenClient->executeCommand('keypoolrefill', 1000)->willReturn($response);

        self::assertNull($this->client->refillKeyPool(1000));
    }

    public function testRefillKeyPoolThrowsExceptionIfRequestFails()
    {
        self::expectException(NodeException::class);

        $response = $this->buildNodeResponse(400);

        $this->guldenClient->executeCommand('keypoolrefill', 1000)->willReturn($response);

        $this->client->refillKeyPool(1000);
    }

    public function testSendToManyReturnsTransactionIdIfRequestSucceeds()
    {
        $transactionId = Fixtures::randomHash();

        $fromAccount = 'from-account';
        $addressesWithAmounts = [
            Fixtures::randomHash() => 123.45,
            Fixtures::randomHash() => 234.56,
            Fixtures::randomHash() => 345.67,
            Fixtures::randomHash() => 456
        ];

        $response = $this->buildNodeResponse(NodeResponse::STATUS_SUCCESS, $transactionId);

        $this->guldenClient->validateAddressFormat(Argument::type('string'))->willReturn(true);
        $this->guldenClient->executeCommand('sendmany', $fromAccount, json_encode($addressesWithAmounts), 1, '', '[]')
            ->willReturn($response);

        self::assertSame($transactionId, $this->client->sendToMany($fromAccount, $addressesWithAmounts));
    }

    public function testSendToManyThrowsExceptionIfNoRecipientsAreSpecified()
    {
        self::expectException(InputException::class);

        $this->client->sendToMany('from-account', []);
    }

    public function testSendToManyThrowsExceptionIfAnyOfTheRecipientsAddressesAppearsToBeInvalid()
    {
        self::expectException(InputException::class);

        $this->guldenClient->validateAddressFormat('address')->willReturn(false);

        $this->client->sendToMany('from-account', ['address' => 123.45]);
    }

    public function testSendToManyThrowsExceptionIfAnyOfTheAmountsIsNotANumericDataType()
    {
        self::expectException(InputException::class);

        $this->guldenClient->validateAddressFormat('address')->willReturn(true);

        $this->client->sendToMany('from-account', ['address' => '123.45']);
    }

    public function testSendToManyThrowsExceptionIfAnyOfTheAmountsIsNegative()
    {
        self::expectException(InputException::class);

        $address = Fixtures::randomAddress();

        $this->guldenClient->validateAddressFormat($address)->willReturn(true);

        $this->client->sendToMany('from-account', [$address => -123.45]);
    }

    public function testSendToManyThrowsExceptionIfSubtractFeeAddressIsNotARecipient()
    {
        self::expectException(InputException::class);

        $address1 = Fixtures::randomAddress();
        $address2 = Fixtures::randomAddress();

        $this->guldenClient->validateAddressFormat($address1)->willReturn(true);

        $this->client->sendToMany('from-account', [$address1 => 123.45], 1, '', [$address2]);
    }

    public function testSendToManyThrowsExceptionIfRequestFails()
    {
        self::expectException(NodeException::class);

        $fromAccount = 'from-account';
        $addressesWithAmounts = [
            Fixtures::randomHash() => 123.45
        ];

        $response = $this->buildNodeResponse(400);

        $this->guldenClient->validateAddressFormat(Argument::type('string'))->willReturn(true);
        $this->guldenClient->executeCommand('sendmany', $fromAccount, json_encode($addressesWithAmounts), 1, '', '[]')
            ->willReturn($response);

        $this->client->sendToMany($fromAccount, $addressesWithAmounts);
    }

    public function testSendToAddressReturnsTransactionIdIfRequestSucceeds()
    {
        $transactionId = Fixtures::randomHash();

        $toAddress = Fixtures::randomAddress();
        $amount = 123.45;

        $response = $this->buildNodeResponse(NodeResponse::STATUS_SUCCESS, $transactionId);

        $this->guldenClient->executeCommand('sendtoaddressfromaccount', '', $toAddress, $amount, '', '', false)
            ->willReturn($response);

        self::assertSame($transactionId, $this->client->sendToAddress($toAddress, $amount));
    }

    public function testSendToAddressThrowsExceptionIfRequestFails()
    {
        self::expectException(NodeException::class);

        $toAddress = Fixtures::randomAddress();
        $amount = 123.45;

        $response = $this->buildNodeResponse(400);

        $this->guldenClient->executeCommand('sendtoaddressfromaccount', '', $toAddress, $amount, '', '', false)
            ->willReturn($response);

        $this->client->sendToAddress($toAddress, $amount);
    }

    public function testSendToAddressFromAccountReturnsTransactionIdIfRequestSucceeds()
    {
        $transactionId = Fixtures::randomHash();

        $fromAccount = 'from-account';
        $toAddress = Fixtures::randomAddress();
        $amount = 123.45;

        $response = $this->buildNodeResponse(NodeResponse::STATUS_SUCCESS, $transactionId);

        $this->guldenClient->executeCommand('sendtoaddressfromaccount', $fromAccount, $toAddress, $amount, '', '', false)
            ->willReturn($response);

        self::assertSame($transactionId, $this->client->sendToAddressFromAccount($fromAccount, $toAddress, $amount));
    }

    public function testSendToAddressFromAccountThrowsExceptionIfRequestFails()
    {
        self::expectException(NodeException::class);

        $fromAccount = 'from-account';
        $toAddress = Fixtures::randomAddress();
        $amount = 123.45;

        $response = $this->buildNodeResponse(400);

        $this->guldenClient->executeCommand('sendtoaddressfromaccount', $fromAccount, $toAddress, $amount, '', '', false)
            ->willReturn($response);

        $this->client->sendToAddressFromAccount($fromAccount, $toAddress, $amount);
    }

    public function testSetTransactionFeeReturnsTrueIfRequestSucceeds()
    {
        $transactionFee = 0.01;

        $response = $this->buildNodeResponse(NodeResponse::STATUS_SUCCESS, true);

        $this->guldenClient->executeCommand('settxfee', $transactionFee)->willReturn($response);

        self::assertSame(true, $this->client->setTransactionFee($transactionFee));
    }

    public function testSetTransactionFeeThrowsExceptionIfRequestFails()
    {
        self::expectException(NodeException::class);

        $transactionFee = 0.01;

        $response = $this->buildNodeResponse(400);

        $this->guldenClient->executeCommand('settxfee', $transactionFee)->willReturn($response);

        $this->client->setTransactionFee($transactionFee);
    }

    public function testSignMessageReturnsSignedMessageIfRequestSucceeds()
    {
        $address = Fixtures::randomAddress();
        $message = 'message';
        $signedMessage = '1234567890abcdefghijklmnopqrstuvwxyz';

        $response = $this->buildNodeResponse(NodeResponse::STATUS_SUCCESS, $signedMessage);

        $this->guldenClient->validateAddressFormat($address)->willReturn(true);
        $this->guldenClient->executeCommand('signmessage', $address, $message)->willReturn($response);

        self::assertSame($signedMessage, $this->client->signMessage($address, $message));
    }

    public function testSignMessageThrowsExceptionIfAddressDoesNotAppearToBeValid()
    {
        self::expectException(InputException::class);

        $address = Fixtures::randomAddress();
        $message = 'message';

        $this->guldenClient->validateAddressFormat($address)->willReturn(false);

        $this->client->signMessage($address, $message);
    }

    public function testSignMessageThrowsExceptionIfRequestFails()
    {
        self::expectException(NodeException::class);

        $address = Fixtures::randomAddress();
        $message = 'message';

        $response = $this->buildNodeResponse(400);

        $this->guldenClient->validateAddressFormat($address)->willReturn(true);
        $this->guldenClient->executeCommand('signmessage', $address, $message)->willReturn($response);

        $this->client->signMessage($address, $message);
    }
}
