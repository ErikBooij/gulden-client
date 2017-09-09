<?php
declare(strict_types=1);

namespace GuldenPHP\Model\Domain;

use DateTime;

class Block
{
    /** @var string */
    private $bits = '';

    /** @var string */
    private $chainWork = '';

    /** @var int */
    private $confirmations = -1;

    /** @var float */
    private $difficulty = -1;

    /** @var string */
    private $hash = '';

    /** @var int */
    private $height = -1;

    /** @var DateTime */
    private $medianTime = null;

    /** @var string */
    private $merkleRoot = '';

    /** @var string */
    private $nextBlockHash = '';

    /** @var int */
    private $nonce = -1;

    /** @var string */
    private $previousBlockHash = '';

    /** @var int */
    private $size = -1;

    /** @var int */
    private $strippedSize = -1;

    /** @var DateTime */
    private $time = null;

    /** @var int */
    private $transactionCount = -1;

    /** @var array */
    private $transactions = [];

    /** @var int */
    private $version = -1;

    /** @var string */
    private $versionHex = '';

    /** @var int */
    private $weight = -1;

    /**
     * @param int      $height
     * @param int      $confirmations
     * @param string   $hash
     * @param string   $nextBlockHash
     * @param string   $previousBlockHash
     * @param float    $difficulty
     * @param string   $chainWork
     * @param string   $merkleRoot
     * @param int      $nonce
     * @param string[] $transactions
     * @param int      $time
     * @param int      $medianTime
     * @param int      $size
     * @param int      $strippedSize
     * @param string   $bits
     * @param int      $version
     * @param string   $versionHex
     * @param int      $weight
     */
    private function __construct(
        int $height,
        int $confirmations,
        string $hash,
        string $previousBlockHash,
        string $nextBlockHash,
        float $difficulty,
        string $chainWork,
        string $merkleRoot,
        int $nonce,
        array $transactions,
        int $time,
        int $medianTime,
        int $size,
        int $strippedSize,
        string $bits,
        int $version,
        string $versionHex,
        int $weight
    ) {
        $this->height = $height;
        $this->confirmations = $confirmations;
        $this->hash = $hash;
        $this->previousBlockHash = $previousBlockHash;
        $this->nextBlockHash = $nextBlockHash;
        $this->difficulty = $difficulty;
        $this->chainWork = $chainWork;
        $this->merkleRoot = $merkleRoot;
        $this->nonce = $nonce;
        $this->transactions = $transactions;
        $this->time = DateTime::createFromFormat('U', (string)$time);
        $this->medianTime = DateTime::createFromFormat('U', (string)$medianTime);
        $this->size = $size;
        $this->strippedSize = $strippedSize;
        $this->bits = $bits;
        $this->version = $version;
        $this->versionHex = $versionHex;
        $this->weight = $weight;

        $this->transactionCount = count($transactions);
    }

    /**
     * @param array $blockInfo
     *
     * @return Block
     */
    public static function fromArray(array $blockInfo): self
    {
        return new static(
            $blockInfo['height'] ?? -1,
            $blockInfo['confirmations'] ?? -1,
            $blockInfo['hash'] ?? '',
            $blockInfo['previousblockhash'] ?? '',
            $blockInfo['nextblockhash'] ?? '',
            $blockInfo['difficulty'] ?? -1,
            $blockInfo['chainwork'] ?? '',
            $blockInfo['merkleroot'] ?? '',
            $blockInfo['nonce'] ?? -1,
            $blockInfo['tx'] ?? [],
            $blockInfo['time'] ?? -1,
            $blockInfo['mediantime'] ?? -1,
            $blockInfo['size'] ?? -1,
            $blockInfo['strippedsize'] ?? -1,
            $blockInfo['bits'] ?? '',
            $blockInfo['version'] ?? -1,
            $blockInfo['versionHex'] ?? '',
            $blockInfo['weight'] ?? -1
        );
    }

    /**
     * @return string
     */
    public function getBits(): string
    {
        return $this->bits;
    }

    /**
     * @return string
     */
    public function getChainWork(): string
    {
        return $this->chainWork;
    }

    /**
     * @return int
     */
    public function getConfirmations(): int
    {
        return $this->confirmations;
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
    public function getHash(): string
    {
        return $this->hash;
    }

    /**
     * @return int
     */
    public function getHeight(): int
    {
        return $this->height;
    }

    /**
     * @return DateTime
     */
    public function getMedianTime(): DateTime
    {
        return $this->medianTime;
    }

    /**
     * @return string
     */
    public function getMerkleRoot(): string
    {
        return $this->merkleRoot;
    }

    /**
     * @return string
     */
    public function getNextBlockHash(): string
    {
        return $this->nextBlockHash;
    }

    /**
     * @return int
     */
    public function getNonce(): int
    {
        return $this->nonce;
    }

    /**
     * @return string
     */
    public function getPreviousBlockHash(): string
    {
        return $this->previousBlockHash;
    }

    /**
     * @return int
     */
    public function getSize(): int
    {
        return $this->size;
    }

    /**
     * @return int
     */
    public function getStrippedSize(): int
    {
        return $this->strippedSize;
    }

    /**
     * @return DateTime
     */
    public function getTime(): DateTime
    {
        return $this->time;
    }

    /**
     * @return int
     */
    public function getTransactionCount(): int
    {
        return $this->transactionCount;
    }

    /**
     * @return array
     */
    public function getTransactions(): array
    {
        return $this->transactions;
    }

    /**
     * @return int
     */
    public function getVersion(): int
    {
        return $this->version;
    }

    /**
     * @return string
     */
    public function getVersionHex(): string
    {
        return $this->versionHex;
    }

    /**
     * @return int
     */
    public function getWeight(): int
    {
        return $this->weight;
    }
}
