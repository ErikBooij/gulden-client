<?php
declare(strict_types=1);

namespace GuldenPHP\SubClient;

use GuldenPHP\GuldenClient;
use GuldenPHP\Model\Domain\Node;

class ControlSubClient extends AbstractSubClient
{
    /**
     * @return Node
     */
    public function getInfo(): Node
    {
        $response = $this->client->executeCommand('getinfo')
            ->throwIfUnsuccessful();

        return Node::fromArray($response->getBody());
    }

    /**
     * @return void
     */
    public function stop()
    {
        $this->client->executeCommand('stop')
            ->throwIfUnsuccessful();
    }

    /**
     * @param GuldenClient $client
     *
     * @return ControlSubClient
     */
    public static function fromClient(GuldenClient $client): self
    {
        return new static($client);
    }
}
