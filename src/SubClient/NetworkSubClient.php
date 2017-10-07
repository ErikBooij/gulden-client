<?php
declare(strict_types=1);

namespace GuldenPHP\SubClient;

use GuldenPHP\GuldenClient;
use GuldenPHP\Model\Domain\PeerInfo;

class NetworkSubClient extends AbstractSubClient
{
    /**
     * @return PeerInfo
     */
    public function getPeerInfo(): PeerInfo
    {
        $response = $this->client->executeCommand('getpeerinfo')
            ->throwIfUnsuccessful();

        return PeerInfo::fromArray($response->getBody());
    }

    /**
     * @param GuldenClient $client
     *
     * @return NetworkSubClient
     */
    public static function fromClient(GuldenClient $client): self
    {
        return new static($client);
    }
}
