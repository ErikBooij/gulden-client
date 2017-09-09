<?php
declare(strict_types=1);

namespace GuldenPHP\Model\Domain;

use DateTime;

class BlockChain
{
    /** @var string */
    private $bestBlockHash = '';

    /** @var int */
    private $blocks = -1;

    /** @var string */
    private $chain = '';

    /** @var string */
    private $chainWork = '';

    /** @var float */
    private $difficulty = -1;

    /** @var int */
    private $headers = -1;

    /** @var DateTime */
    private $medianTime = null;

    /** @var bool */
    private $pruned = false;

    /** @var array */
    private $softForks = [];

    /** @var float */
    private $verificationProgress = -1;

    /**
     * @param string $chain
     * @param int    $blocks
     * @param int    $headers
     * @param string $bestBlockHash
     * @param float  $verificationProgress
     * @param string $chainWork
     * @param float  $difficulty
     * @param int    $medianTime
     * @param bool   $pruned
     * @param array  $softForks
     */
    private function __construct(
        string $chain,
        int $blocks,
        int $headers,
        string $bestBlockHash,
        float $verificationProgress,
        string $chainWork,
        float $difficulty,
        int $medianTime,
        bool $pruned,
        array $softForks
    ) {
        $this->chain = $chain;
        $this->blocks = $blocks;
        $this->headers = $headers;
        $this->bestBlockHash = $bestBlockHash;
        $this->verificationProgress = $verificationProgress;
        $this->chainWork = $chainWork;
        $this->difficulty = $difficulty;
        $this->medianTime = DateTime::createFromFormat('U', (string)$medianTime);
        $this->pruned = $pruned;
        $this->softForks = $softForks;
    }

    /**
     * @param array $blockChainInfo
     *
     * @return BlockChain
     */
    public static function fromArray(array $blockChainInfo): self
    {
        return new static(
            $blockChainInfo['chain'] ?? '',
            $blockChainInfo['blocks'] ?? -1,
            $blockChainInfo['headers'] ?? -1,
            $blockChainInfo['bestblockhash'] ?? '',
            $blockChainInfo['verificationprogress'] ?? -1,
            $blockChainInfo['chainwork'] ?? '',
            $blockChainInfo['difficulty'] ?? -1,
            $blockChainInfo['mediantime'] ?? -1,
            $blockChainInfo['pruned'] ?? false,
            $blockChainInfo['softforks'] ?? []
        );
    }

    /**
     * @return string
     */
    public function getBestBlockHash(): string
    {
        return $this->bestBlockHash;
    }

    /**
     * @return int
     */
    public function getBlocks(): int
    {
        return $this->blocks;
    }

    /**
     * @return string
     */
    public function getChain(): string
    {
        return $this->chain;
    }

    /**
     * @return string
     */
    public function getChainWork(): string
    {
        return $this->chainWork;
    }

    /**
     * @return float
     */
    public function getDifficulty(): float
    {
        return $this->difficulty;
    }

    /**
     * @return int
     */
    public function getHeaders(): int
    {
        return $this->headers;
    }

    /**
     * @return DateTime
     */
    public function getMedianTime(): DateTime
    {
        return $this->medianTime;
    }

    /**
     * @return bool
     */
    public function isPruned(): bool
    {
        return $this->pruned;
    }

    /**
     * @return array
     */
    public function getSoftForks(): array
    {
        return $this->softForks;
    }

    /**
     * @return float
     */
    public function getVerificationProgress(): float
    {
        return $this->verificationProgress;
    }
}
