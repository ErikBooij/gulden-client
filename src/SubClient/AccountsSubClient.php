<?php
declare(strict_types=1);

namespace GuldenPHP\SubClient;

use GuldenPHP\GuldenClient;
use GuldenPHP\Model\Domain\Account;
use GuldenPHP\Model\NodeException;

class AccountsSubClient extends AbstractSubClient
{
    /**
     * @param string $account
     * @param string $name
     *
     * @return string
     * @throws NodeException
     */
    public function changeAccountName(string $account, string $name): string
    {
        $response = $this->client->executeCommand('changeaccountname', $account, $name)
            ->throwIfUnsuccessful();

        return (string)$response->getBody();
    }

    /**
     * @param string $name
     *
     * @return string
     * @throws NodeException
     */
    public function createAccount(string $name): string
    {
        $response = $this->client->executeCommand('createaccount', $name)
            ->throwIfUnsuccessful();

        return (string)$response->getBody();
    }

    /**
     * @param string $account
     * @param bool   $force
     *
     * @return bool
     * @throws NodeException
     */
    public function deleteAccount(string $account, bool $force = false): bool
    {
        $response = $this->client->executeCommand('deleteaccount', $account, $force)
            ->throwIfUnsuccessful();

        return (bool)$response->getBody();
    }

    /**
     * Keep in mind that this method returns an Account without 'type' or 'HDindex'.
     *
     * @param string $address
     *
     * @return Account
     * @throws NodeException
     */
    public function getAccount(string $address): Account
    {
        $response = $this->client->executeCommand('getaccount', $address)
            ->throwIfUnsuccessful();

        $accountInfo = $response->getBody();

        if (count($accountInfo) === 0) {
            throw NodeException::withMessage("No account found for address {$address}");
        }

        return Account::fromArray([
            'UUID' => $accountInfo[0][0][0],
            'label' => $accountInfo[0][0][1],
            'type' => '',
            'HDindex' => -1
        ]);
    }

    /**
     * @return string
     * @throws NodeException
     */
    public function getActiveAccount(): string
    {
        $response = $this->client->executeCommand('getactiveaccount')
            ->throwIfUnsuccessful();

        return (string)$response->getBody();
    }

    /**
     * @param string $account
     *
     * @return array
     * @throws NodeException
     */
    public function getAddressesByAccount(string $account): array
    {
        $response = $this->client->executeCommand('getaddressesbyaccount', $account)
            ->throwIfUnsuccessful();

        return $response->getBody() ?? [];
    }

    /**
     * @param string $account
     *
     * @return string
     * @throws NodeException
     */
    public function getReadOnlyAccount(string $account): string
    {
        $response = $this->client->executeCommand('getreadonlyaccount', $account)
            ->throwIfUnsuccessful();

        return (string)$response->getBody();
    }

    /**
     * @param string $name
     * @param string $encodedKey
     *
     * @return string
     * @throws NodeException
     */
    public function importReadOnlyAccount(string $name, string $encodedKey): string
    {
        $response = $this->client->executeCommand('importreadonlyaccount', $name, $encodedKey)
            ->throwIfUnsuccessful();

        return (string)$response->getBody();
    }

    /**
     * @param string|null $seed
     *
     * @return Account[]
     * @throws NodeException
     */
    public function listAccounts(string $seed = null): array
    {
        $response = $this->client->executeCommand('listaccounts', $seed)
            ->throwIfUnsuccessful();

        return array_map(function (array $account): Account {
            return Account::fromArray($account);
        }, $response->getBody() ?? []);
    }

    /**
     * @param string $account
     *
     * @return bool
     * @throws NodeException
     */
    public function setActiveAccount(string $account): bool
    {
        $response = $this->client->executeCommand('setactiveaccount', $account)
            ->throwIfUnsuccessful();

        return strcasecmp($response->getBody(), $account) === 0;
    }

    /**
     * @param GuldenClient $client
     *
     * @return AccountsSubClient
     * @throws NodeException
     */
    public static function fromClient(GuldenClient $client): self
    {
        return new static($client);
    }
}
