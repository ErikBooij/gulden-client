<?php
declare(strict_types=1);

namespace GuldenPHP\SubClient;

use GuldenPHP\GuldenClient;

abstract class AbstractSubClient
{
    /** @var GuldenClient */
    protected $client;

    /**
     * @param GuldenClient $client
     */
    protected function __construct(GuldenClient $client)
    {
        $this->client = $client;
    }
}
