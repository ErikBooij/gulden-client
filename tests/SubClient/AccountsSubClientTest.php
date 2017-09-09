<?php

namespace GuldenPHP\Tests\SubClient;

use GuldenPHP\GuldenClient;
use GuldenPHP\Model\Domain\Account;
use GuldenPHP\Model\NodeException;
use GuldenPHP\Model\NodeResponse;
use GuldenPHP\SubClient\AccountsSubClient;
use GuldenPHP\Tests\ClientTestHelper;
use GuldenPHP\Tests\Fixtures\Fixtures;
use Prophecy\Prophecy\ObjectProphecy;

/**
 * @covers \GuldenPHP\SubClient\AccountsSubClient
 */
class AccountsSubClientTest extends ClientTestHelper
{
    /** @var AccountsSubClient */
    private $client;

    /** @var ObjectProphecy|GuldenClient */
    private $guldenClient;

    public function setUp()
    {
        $this->guldenClient = self::prophesize(GuldenClient::class);

        $this->client = AccountsSubClient::fromClient($this->guldenClient->reveal());
    }

    public function testChangeAccountNameReturnsFinalLabelIfRequestSucceeds()
    {
        $accountNameNew = 'test-account-new';
        $accountNameOld = 'test-account-old';

        $response = $this->buildNodeResponse(NodeResponse::STATUS_SUCCESS, $accountNameNew);

        $this->guldenClient->executeCommand('changeaccountname', $accountNameOld,
            $accountNameNew)->willReturn($response);

        self::assertSame($accountNameNew, $this->client->changeAccountName($accountNameOld, $accountNameNew));
    }

    public function testChangeAccountNameThrowsExceptionIfRequestFails()
    {
        self::expectException(NodeException::class);

        $accountNameNew = 'test-account-new';
        $accountNameOld = 'test-account-old';

        $response = $this->buildNodeResponse(400);

        $this->guldenClient->executeCommand('changeaccountname', $accountNameOld,
            $accountNameNew)->willReturn($response);

        $this->client->changeAccountName($accountNameOld, $accountNameNew);
    }

    public function testCreateAccountReturnsAccountIdIfRequestSucceeds()
    {
        $accountName = 'test-account';
        $accountUuid = Fixtures::randomUuid();

        $response = $this->buildNodeResponse(NodeResponse::STATUS_SUCCESS, $accountUuid);

        $this->guldenClient->executeCommand('createaccount', $accountName)->willReturn($response);

        self::assertSame($accountUuid, $this->client->createAccount($accountName));
    }

    public function testCreateAccountThrowsExceptionIfRequestFails()
    {
        self::expectException(NodeException::class);

        $accountName = 'test-account';

        $response = $this->buildNodeResponse(400);

        $this->guldenClient->executeCommand('createaccount', $accountName)->willReturn($response);

        $this->client->createAccount($accountName);
    }

    public function testDeleteAccountReturnsTrueIfRequestSucceeds()
    {
        $accountName = 'test-account';

        $response = $this->buildNodeResponse(NodeResponse::STATUS_SUCCESS, true);

        $this->guldenClient->executeCommand('deleteaccount', $accountName, false)->willReturn($response);

        self::assertTrue($this->client->deleteAccount($accountName));
    }

    public function testDeleteAccountThrowsExceptionIfRequestFails()
    {
        self::expectException(NodeException::class);

        $accountName = 'test-account';

        $response = $this->buildNodeResponse(400);

        $this->guldenClient->executeCommand('deleteaccount', $accountName, false)->willReturn($response);

        $this->client->deleteAccount($accountName);
    }

    public function testGetAccountReturnsAccountIfRequestSucceeds()
    {
        $address = Fixtures::randomAddress();
        $response = $this->buildNodeResponse(NodeResponse::STATUS_SUCCESS, [
            [
                Fixtures::simplifiedAccount()
            ]
        ]);

        $this->guldenClient->executeCommand('getaccount', $address)->willReturn($response);

        self::assertInstanceOf(Account::class, $this->client->getAccount($address));
    }

    public function testGetAccountThrowsExceptionIfNoAccountMatchesTheGivenAddress()
    {
        self::expectException(NodeException::class);

        $address = Fixtures::randomAddress();
        $response = $this->buildNodeResponse(NodeResponse::STATUS_SUCCESS, []);

        $this->guldenClient->executeCommand('getaccount', $address)->willReturn($response);

        $this->client->getAccount($address);
    }

    public function testGetAccountThrowsExceptionIfRequestFails()
    {
        self::expectException(NodeException::class);

        $address = Fixtures::randomAddress();
        $response = $this->buildNodeResponse(400);

        $this->guldenClient->executeCommand('getaccount', $address)->willReturn($response);

        $this->client->getAccount($address);
    }

    public function testGetActiveAccountReturnsAccountIdIfRequestSucceeds()
    {
        $accountId = Fixtures::randomUuid();

        $response = $this->buildNodeResponse(NodeResponse::STATUS_SUCCESS, $accountId);

        $this->guldenClient->executeCommand('getactiveaccount')->willReturn($response);

        self::assertSame($accountId, $this->client->getActiveAccount());
    }

    public function testGetActiveAccountThrowsExceptionIfRequestFails()
    {
        self::expectException(NodeException::class);

        $response = $this->buildNodeResponse(400);

        $this->guldenClient->executeCommand('getactiveaccount')->willReturn($response);

        $this->client->getActiveAccount();
    }

    public function testGetAddressesByAccountReturnsAddressesIfRequestSucceeds()
    {
        $addresses = [
            Fixtures::randomAddress(),
            Fixtures::randomAddress()
        ];

        $accountId = Fixtures::randomUuid();

        $response = $this->buildNodeResponse(NodeResponse::STATUS_SUCCESS, $addresses);

        $this->guldenClient->executeCommand('getaddressesbyaccount', $accountId)->willReturn($response);

        self::assertSame($addresses, $this->client->getAddressesByAccount($accountId));
    }

    public function testGetAddressesByAccountThrowsExceptionIfRequestFails()
    {
        self::expectException(NodeException::class);

        $accountId = Fixtures::randomUuid();

        $response = $this->buildNodeResponse(400);

        $this->guldenClient->executeCommand('getaddressesbyaccount', $accountId)->willReturn($response);

        $this->client->getAddressesByAccount($accountId);
    }

    public function testGetReadOnlyAccountReturnsEncodedPublicKeyIfRequestSucceeds()
    {
        $accountId = Fixtures::randomUuid();
        $encodedPublicKey = Fixtures::randomHash();

        $response = $this->buildNodeResponse(NodeResponse::STATUS_SUCCESS, $encodedPublicKey);

        $this->guldenClient->executeCommand('getreadonlyaccount', $accountId)->willReturn($response);

        self::assertSame($encodedPublicKey, $this->client->getReadOnlyAccount($accountId));
    }

    public function testGetReadOnlyAccountThrowsExceptionIfRequestFails()
    {
        self::expectException(NodeException::class);

        $accountId = Fixtures::randomUuid();

        $response = $this->buildNodeResponse(400);

        $this->guldenClient->executeCommand('getreadonlyaccount', $accountId)->willReturn($response);

        $this->client->getReadOnlyAccount($accountId);
    }

    public function testImportReadOnlyAccountReturnsAccountIdIfRequestSucceeds()
    {
        $accountName = 'test-account';
        $accountId = Fixtures::randomUuid();
        $encodedPublicKey = Fixtures::randomHash();

        $response = $this->buildNodeResponse(NodeResponse::STATUS_SUCCESS, $accountId);

        $this->guldenClient->executeCommand('importreadonlyaccount', $accountName,
            $encodedPublicKey)->willReturn($response);

        self::assertSame($accountId, $this->client->importReadOnlyAccount($accountName, $encodedPublicKey));
    }

    public function testImportReadOnlyAccountThrowsExceptionIfRequestFails()
    {
        self::expectException(NodeException::class);

        $accountName = 'test-account';
        $encodedPublicKey = Fixtures::randomHash();

        $response = $this->buildNodeResponse(400);

        $this->guldenClient->executeCommand('importreadonlyaccount', $accountName,
            $encodedPublicKey)->willReturn($response);

        $this->client->importReadOnlyAccount($accountName, $encodedPublicKey);
    }

    public function testListAccountsReturnsArrayOfAccountsIfRequestSucceeds()
    {
        $accountsData = [
            Fixtures::account(),
            Fixtures::account(),
            Fixtures::account()
        ];

        $response = $this->buildNodeResponse(NodeResponse::STATUS_SUCCESS, $accountsData);

        $this->guldenClient->executeCommand('listaccounts', null)->willReturn($response);

        $accounts = $this->client->listAccounts();

        self::assertCount(3, $accounts);
        self::assertContainsOnlyInstancesOf(Account::class, $accounts);
    }

    public function testListAccountsThrowsExceptionIfRequestFails()
    {
        self::expectException(NodeException::class);

        $response = $this->buildNodeResponse(400);

        $this->guldenClient->executeCommand('listaccounts', null)->willReturn($response);

        $this->client->listAccounts();
    }

    public function testSetActiveAccountReturnsTrueIfRequestSucceeds()
    {
        $account = 'test-account';

        $response = $this->buildNodeResponse(NodeResponse::STATUS_SUCCESS, $account);

        $this->guldenClient->executeCommand('setactiveaccount', $account)->willReturn($response);

        self::assertTrue($this->client->setActiveAccount($account));
    }

    public function testSetActiveAccountThrowsExceptionIfRequestFails()
    {
        self::expectException(NodeException::class);

        $account = 'test-account';

        $response = $this->buildNodeResponse(400);

        $this->guldenClient->executeCommand('setactiveaccount', $account)->willReturn($response);

        $this->client->setActiveAccount($account);
    }
}
