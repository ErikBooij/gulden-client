<?php
declare(strict_types=1);

namespace GuldenPHP\Model\Domain;

use DateTime;

class Peer
{
    /** @var DateTime */
    private $connectionTime;

    /** @var bool */
    private $inbound;

    /** @var PeerAddress */
    private $localAddress;

    /** @var float */
    private $minimumPingTime;

    /** @var float */
    private $pingTime;

    /** @var bool */
    private $relayTransactions;

    /** @var PeerAddress */
    private $remoteAddress;

    /** @var string */
    private $subVersion;

    /** @var int */
    private $timeOffset;

    /** @var int */
    private $version;

    private function __construct(
        PeerAddress $remoteAddress,
        PeerAddress $localAddress,
        bool $inbound,
        int $version,
        string $subVersion,
        DateTime $connected,
        int $timeOffset,
        bool $relayTransactions,
        float $pingTime,
        float $minimumPingTime
    ) {
        $this->remoteAddress = $remoteAddress;
        $this->localAddress = $localAddress;
        $this->inbound = $inbound;
        $this->version = $version;
        $this->subVersion = $subVersion;
        $this->connectionTime = $connected;
        $this->timeOffset = $timeOffset;
        $this->relayTransactions = $relayTransactions;
        $this->pingTime = $pingTime;
        $this->minimumPingTime = $minimumPingTime;
    }

    /**
     * @param mixed[] $peer
     *
     * @return Peer
     */
    public static function fromArray(array $peer): self
    {
        return new static(
            PeerAddress::fromString($peer['addr'] ?? ''),
            PeerAddress::fromString($peer['addrlocal'] ?? ''),
            $peer['inbound'] ?? false,
            $peer['version'] ?? 0,
            trim($peer['subver'] ?? '', '/'),
            DateTime::createFromFormat('U', (string)($peer['conntime'] ?? '0')),
            $peer['timeoffset'] ?? 0,
            $peer['relaytxes'] ?? false,
            $peer['pingtime'] ?? 0,
            $peer['minping'] ?? 0
        );
    }

    /**
     * @return DateTime
     */
    public function getConnectionTime(): DateTime
    {
        return $this->connectionTime;
    }

    /**
     * @return bool
     */
    public function isInbound(): bool
    {
        return $this->inbound;
    }

    /**
     * @return PeerAddress
     */
    public function getLocalAddress(): PeerAddress
    {
        return $this->localAddress;
    }

    /**
     * @return float
     */
    public function getMinimumPingTime(): float
    {
        return $this->minimumPingTime;
    }

    /**
     * @return float
     */
    public function getPingTime(): float
    {
        return $this->pingTime;
    }

    /**
     * @return bool
     */
    public function getRelayTransactions(): bool
    {
        return $this->relayTransactions;
    }

    /**
     * @return PeerAddress
     */
    public function getRemoteAddress(): PeerAddress
    {
        return $this->remoteAddress;
    }

    /**
     * @return string
     */
    public function getSubVersion(): string
    {
        return $this->subVersion;
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
