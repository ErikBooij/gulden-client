<?php
declare(strict_types=1);

namespace GuldenPHP\Model\Domain;

class Node
{
    /** @var int */
    private $blocks = -1;

    /** @var int */
    private $connections = -1;

    /** @var float */
    private $difficulty = -1;

    /** @var string */
    private $errors = '';

    /** @var float */
    private $minInput = -1;

    /** @var float */
    private $payTransactionFee = -1;

    /** @var int */
    private $protocolVersion = -1;

    /** @var string */
    private $proxy = '';

    /** @var float */
    private $relayFee = -1;

    /** @var bool */
    private $testNet = false;

    /** @var int */
    private $timeOffset = -1;

    /** @var int */
    private $version = -1;

    /**
     * @param int    $blocks
     * @param int    $connections
     * @param float  $difficulty
     * @param string $errors
     * @param float  $minInput
     * @param float  $payTransactionFee
     * @param int    $protocolVersion
     * @param string $proxy
     * @param float  $relayFee
     * @param bool   $testNet
     * @param int    $timeOffset
     * @param int    $version
     */
    private function __construct(
        int $blocks,
        int $connections,
        float $difficulty,
        string $errors,
        float $minInput,
        float $payTransactionFee,
        int $protocolVersion,
        string $proxy,
        float $relayFee,
        bool $testNet,
        int $timeOffset,
        int $version
    ) {
        $this->blocks = $blocks;
        $this->connections = $connections;
        $this->difficulty = $difficulty;
        $this->errors = $errors;
        $this->minInput = $minInput;
        $this->payTransactionFee = $payTransactionFee;
        $this->protocolVersion = $protocolVersion;
        $this->proxy = $proxy;
        $this->relayFee = $relayFee;
        $this->testNet = $testNet;
        $this->timeOffset = $timeOffset;
        $this->version = $version;
    }

    /**
     * @param array $nodeInfo
     *
     * @return Node
     */
    public static function fromArray(array $nodeInfo): self
    {
        Return new static(
            $nodeInfo['blocks'] ?? -1,
            $nodeInfo['connections'] ?? -1,
            $nodeInfo['difficulty'] ?? -1,
            $nodeInfo['errors'] ?? '',
            $nodeInfo['mininput'] ?? -1,
            $nodeInfo['paytxfee'] ?? -1,
            $nodeInfo['protocolversion'] ?? -1,
            $nodeInfo['proxy'] ?? '',
            $nodeInfo['relayfee'] ?? -1,
            $nodeInfo['testnet'] ?? false,
            $nodeInfo['timeoffset'] ?? -1,
            $nodeInfo['version'] ?? -1
        );
    }

    /**
     * @return int
     */
    public function getBlocks(): int
    {
        return $this->blocks;
    }

    /**
     * @return int
     */
    public function getConnections(): int
    {
        return $this->connections;
    }

    /**
     * @return float
     */
    public function getDifficulty(): float
    {
        return $this->difficulty;
    }

    /**
     * @return string
     */
    public function getErrors(): string
    {
        return $this->errors;
    }

    /**
     * @return float
     */
    public function getMinInput(): float
    {
        return $this->minInput;
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
    public function getProtocolVersion(): int
    {
        return $this->protocolVersion;
    }

    /**
     * @return string
     */
    public function getProxy(): string
    {
        return $this->proxy;
    }

    /**
     * @return float
     */
    public function getRelayFee(): float
    {
        return $this->relayFee;
    }

    /**
     * @return bool
     */
    public function isTestNet(): bool
    {
        return $this->testNet;
    }

    /**
     * @return int
     */
    public function getTimeOffset(): int
    {
        return $this->timeOffset;
    }

    /**
     * @return int
     */
    public function getVersion(): int
    {
        return $this->version;
    }
}
