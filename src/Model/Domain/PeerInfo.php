<?php
declare(strict_types=1);

namespace GuldenPHP\Model\Domain;

class PeerInfo
{
    /** @var Peer[] */
    private $inboundPeers;

    /** @var Peer[] */
    private $outboundPeers;

    /** @var Peer[] */
    private $peers;

    /**
     * @param array $peers
     */
    private function __construct(array $peers)
    {
        $this->peers = $peers;

        usort($this->peers, [$this, 'sortPeers']);
    }

    /**
     * @param array $peerInfo
     *
     * @return PeerInfo
     */
    public static function fromArray(array $peerInfo): self
    {
        $peers = array_reduce($peerInfo, function (array $carry, array $peer): array {
            $carry[] = Peer::fromArray($peer);

            return $carry;
        }, []);

        return new static($peers);
    }

    /**
     * @return Peer[]
     */
    public function getInboundPeers(): array
    {
        if (!is_array($this->inboundPeers)) {
            $this->inboundPeers = array_values(array_filter($this->peers, function (Peer $peer): bool {
                return $peer->isInbound();
            }));
        }

        return $this->inboundPeers;
    }

    /**
     * @return Peer[]
     */
    public function getOutboundPeers(): array
    {
        if (!is_array($this->outboundPeers)) {
            $this->outboundPeers = array_values(array_filter($this->peers, function (Peer $peer): bool {
                return !$peer->isInbound();
            }));
        }

        return $this->outboundPeers;
    }

    /**
     * @return Peer[]
     */
    public function getPeers(): array
    {
        return array_values($this->peers);
    }

    /**
     * @return int
     */
    public function numberOfInboundPeers(): int
    {
        return count($this->getInboundPeers());
    }

    /**
     * @return int
     */
    public function numberOfOutboundPeers(): int
    {
        return count($this->getOutboundPeers());
    }

    /**
     * @return int
     */
    public function numberOfPeers(): int
    {
        return count($this->peers);
    }

    /**
     * @param Peer $peerA
     * @param Peer $peerB
     *
     * @return int
     */
    private function sortPeers(Peer $peerA, Peer $peerB): int
    {
        return $peerA->getConnectionTime() <=> $peerB->getConnectionTime();
    }
}
