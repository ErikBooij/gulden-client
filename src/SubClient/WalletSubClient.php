<?php
declare(strict_types=1);

namespace GuldenPHP\SubClient;

use GuldenPHP\GuldenClient;
use GuldenPHP\Model\Domain\Wallet;
use GuldenPHP\Model\InputException;
use GuldenPHP\Model\NodeException;

class WalletSubClient extends AbstractSubClient
{
    /**
     * @param string $transactionId
     *
     * @return void
     * @throws NodeException
     */
    public function abandonTransaction(string $transactionId)
    {
        $this->client->executeCommand('abandontransaction', $transactionId)
            ->throwIfUnsuccessful();
    }

    /**
     * @param string $filename
     *
     * @return void
     * @throws NodeException
     */
    public function backUpWallet(string $filename)
    {
        $this->client->executeCommand('backupwallet', $filename)
            ->throwIfUnsuccessful();
    }

    /**
     * @param string $account
     * @param int    $minConf
     * @param bool   $includeWatchOnly
     *
     * @return float
     * @throws NodeException
     */
    public function getBalance(string $account = '*', int $minConf = 0, bool $includeWatchOnly = false): float
    {
        $response = $this->client->executeCommand('getbalance', $account, $minConf, $includeWatchOnly)
            ->throwIfUnsuccessful();

        return (float)$response->getBody();
    }

    /**
     * @param string $account
     *
     * @return string
     * @throws NodeException
     */
    public function getNewAddress(string $account): string
    {
        $response = $this->client->executeCommand('getnewaddress', $account)
            ->throwIfUnsuccessful();

        return (string)$response->getBody();
    }

    /**
     * @return string
     * @throws NodeException
     */
    public function getRawChangeAddress(): string
    {
        $response = $this->client->executeCommand('getrawchangeaddress')
            ->throwIfUnsuccessful();

        return (string)$response->getBody();
    }

    /**
     * @return float
     * @throws NodeException
     */
    public function getUnconfirmedBalance(): float
    {
        $response = $this->client->executeCommand('getunconfirmedbalance')
            ->throwIfUnsuccessful();

        return (float)$response->getBody();
    }

    /**
     * @return Wallet
     * @throws NodeException
     */
    public function getWalletInfo(): Wallet
    {
        $response = $this->client->executeCommand('getwalletinfo')
            ->throwIfUnsuccessful();

        return Wallet::fromArray($response->getBody() ?? []);
    }

    /**
     * @param string $fromAccount
     * @param string $toAccount
     * @param float  $amount
     * @param int    $minimumConfirmations
     * @param string $comment
     *
     * @return bool
     * @throws InputException
     * @throws NodeException
     */
    public function move(
        string $fromAccount,
        string $toAccount,
        float $amount,
        int $minimumConfirmations = 1,
        string $comment = ''
    ): bool {
        if ($amount <= 0) {
            throw InputException::withMessage("Can't move an amount of {$amount} because it's less than 0");
        }

        $response = $this->client->executeCommand(
            'move',
            $fromAccount,
            $toAccount,
            $amount,
            $minimumConfirmations,
            $comment
        )->throwIfUnsuccessful();

        return (bool)$response->getBody();
    }

    /**
     * @param int $size
     *
     * @return void
     * @throws NodeException
     */
    public function refillKeyPool(int $size)
    {
        $this->client->executeCommand('keypoolrefill', $size)
            ->throwIfUnsuccessful();
    }

    /**
     * @param string $fromAccount
     * @param array  $addressesWithAmounts
     * @param int    $minimumConfirmations
     * @param string $comment
     * @param array  $subtractFeeFromAddresses
     *
     * @return string
     * @throws InputException
     * @throws NodeException
     */
    public function sendToMany(
        string $fromAccount,
        array $addressesWithAmounts,
        int $minimumConfirmations = 1,
        string $comment = '',
        array $subtractFeeFromAddresses = []
    ): string {
        if (count($addressesWithAmounts) === 0) {
            throw InputException::withMessage('No addresses and amounts were defined');
        }

        foreach ($addressesWithAmounts as $address => $amount) {
            if (!$this->client->validateAddressFormat($address)) {
                throw InputException::withMessage("{$address} does not appear to be a valid Gulden address");
            }

            if (!is_float($amount) && !is_int($amount)) {
                throw InputException::withMessage("Can't send {$amount} NLG to {$address} because it's not a number");
            }

            if ($amount <= 0) {
                throw InputException::withMessage("Can't send {$amount} NLG to {$address} because it's not greater than 0");
            }
        }

        foreach ($subtractFeeFromAddresses as $address) {
            if (!array_key_exists($address, $addressesWithAmounts)) {
                throw InputException::withMessage("Can't subtract fee from {$address} because it's not listed as a recipient");
            }
        }

        $response = $this->client->executeCommand(
            'sendmany',
            $fromAccount,
            json_encode($addressesWithAmounts),
            $minimumConfirmations,
            $comment,
            json_encode($subtractFeeFromAddresses)
        )->throwIfUnsuccessful();

        return (string)$response->getBody();
    }

    /**
     * @param string $toAddress
     * @param float  $amount
     * @param string $comment
     * @param string $commentTo
     *
     * @param bool   $subtractFeeFromAmount
     *
     * @return string
     * @throws NodeException
     */
    public function sendToAddress(
        string $toAddress,
        float $amount,
        string $comment = '',
        string $commentTo = '',
        bool $subtractFeeFromAmount = false
    ): string {
        return $this->sendToAddressFromAccount('', $toAddress, $amount, $comment, $commentTo, $subtractFeeFromAmount);
    }

    /**
     * @param string $fromAccount
     * @param string $toAddress
     * @param float  $amount
     * @param string $comment
     * @param string $commentTo
     * @param bool   $subtractFeeFromAmount
     *
     * @return string
     * @throws NodeException
     */
    public function sendToAddressFromAccount(
        string $fromAccount,
        string $toAddress,
        float $amount,
        string $comment = '',
        string $commentTo = '',
        bool $subtractFeeFromAmount = false
    ): string {
        $response = $this->client->executeCommand(
            'sendtoaddressfromaccount',
            $fromAccount,
            $toAddress,
            $amount,
            $comment,
            $commentTo,
            $subtractFeeFromAmount
        )->throwIfUnsuccessful();

        return (string)$response->getBody();
    }

    /**
     * @param float $transactionFee
     *
     * @return bool
     * @throws NodeException
     */
    public function setTransactionFee(float $transactionFee): bool
    {
        $response = $this->client->executeCommand('settxfee', $transactionFee)
            ->throwIfUnsuccessful();

        return (bool)$response->getBody();
    }

    /**
     * @param string $address
     * @param string $message
     *
     * @return string
     * @throws InputException
     * @throws NodeException
     */
    public function signMessage(string $address, string $message): string
    {
        if (!$this->client->validateAddressFormat($address)) {
            throw InputException::withMessage("{$address} does not appear to be a valid address");
        }

        $response = $this->client->executeCommand('signmessage', $address, $message)
            ->throwIfUnsuccessful();

        return (string)$response->getBody();
    }

    /**
     * @param GuldenClient $client
     *
     * @return WalletSubClient
     */
    public static function fromClient(GuldenClient $client): self
    {
        return new static($client);
    }
}
