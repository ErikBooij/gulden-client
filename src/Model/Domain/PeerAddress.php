<?php
declare(strict_types=1);

namespace GuldenPHP\Model\Domain;

class PeerAddress
{
    /** @var string */
    private $address;

    /** @var int */
    private $port;

    /**
     * @param string $address
     * @param int    $port
     */
    private function __construct(string $address, int $port)
    {
        $this->address = $address;
        $this->port = $port;
    }

    /**
     * @param string $address
     *
     * @return PeerAddress
     */
    public static function fromString(string $address): self
    {
        list($host, $port) = explode(':', $address . ':');

        return new self($host, (int)$port);
    }

    /**
     * @return string
     */
    public function getAddress(): string
    {
        return $this->address;
    }

    /**
     * @return int
     */
    public function getPort(): int
    {
        return $this->port;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return "{$this->address}:{$this->port}";
    }
}
