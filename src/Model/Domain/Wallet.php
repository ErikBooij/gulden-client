<?php
declare(strict_types=1);

namespace GuldenPHP\Model\Domain;

class Wallet
{
    /** @var float */
    private $balance = -1;

    /** @var float */
    private $immatureBalance = -1;

    /** @var int */
    private $keyPoolOldest = -1;

    /** @var float */
    private $payTransactionFee = -1;

    /** @var int */
    private $transactionCount = -1;

    /** @var float */
    private $unconfirmedBalance = -1;

    /** @var int */
    private $walletVersion = -1;

    /**
     * @param float $balance
     * @param float $immatureBalance
     * @param int   $keypoolOldest
     * @param float $payTransactionFee
     * @param int   $transactionCount
     * @param float $unconfirmedBalance
     * @param int   $walletVersion
     */
    private function __construct(
        float $balance,
        float $immatureBalance,
        int $keypoolOldest,
        float $payTransactionFee,
        int $transactionCount,
        float $unconfirmedBalance,
        int $walletVersion
    ) {
        $this->balance = $balance;
        $this->immatureBalance = $immatureBalance;
        $this->keyPoolOldest = $keypoolOldest;
        $this->payTransactionFee = $payTransactionFee;
        $this->transactionCount = $transactionCount;
        $this->unconfirmedBalance = $unconfirmedBalance;
        $this->walletVersion = $walletVersion;
    }

    /**
     * @param array $walletInfo
     *
     * @return Wallet
     */
    public static function fromArray(array $walletInfo): self
    {
        return new static(
            $walletInfo['balance'] ?? -1,
            $walletInfo['immature_balance'] ?? -1,
            $walletInfo['keypoololdest'] ?? -1,
            $walletInfo['paytxfee'] ?? -1,
            $walletInfo['txcount'] ?? -1,
            $walletInfo['unconfirmed_balance'] ?? -1,
            $walletInfo['walletversion'] ?? -1
        );
    }

    /**
     * @return float
     */
    public function getBalance(): float
    {
        return $this->balance;
    }

    /**
     * @return float
     */
    public function getImmatureBalance(): float
    {
        return $this->immatureBalance;
    }

    /**
     * @return int
     */
    public function getKeyPoolOldest(): int
    {
        return $this->keyPoolOldest;
    }

    /**
     * @return float
     */
    public function getPayTransactionFee(): float
    {
        return $this->payTransactionFee;
    }

    /**
     * @return int
     */
    public function getTransactionCount(): int
    {
        return $this->transactionCount;
    }

    /**
     * @return float
     */
    public function getUnconfirmedBalance(): float
    {
        return $this->unconfirmedBalance;
    }

    /**
     * @return int
     */
    public function getWalletVersion(): int
    {
        return $this->walletVersion;
    }
}
