<?php
declare(strict_types=1);

namespace GuldenPHP;

use GuldenPHP\Model\NodeException;
use GuldenPHP\Model\NodeResponse;
use GuldenPHP\SubClient\AccountsSubClient;
use GuldenPHP\SubClient\BlockChainSubClient;
use GuldenPHP\SubClient\ControlSubClient;
use GuldenPHP\SubClient\WalletSubClient;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Psr7\Request;

class GuldenClient
{
    /** @var string */
    private $host;

    /** @var Client */
    private $httpClient;

    /** @var string */
    private $password;

    /** @var int */
    private $port;

    /** @var array */
    private $subClients = [];

    /** @var string */
    private $username;

    /**
     * @param string $username
     * @param string $password
     * @param string $host
     * @param int    $port
     * @param Client $httpClient
     */
    public function __construct(
        string $username,
        string $password,
        string $host = 'http://127.0.0.1',
        int $port = 9232,
        Client $httpClient = null
    ) {
        $httpClient = $httpClient ?? new Client;

        if (stripos($host, 'http') !== 0) {
            $host = 'http://' . $host;
        }

        if ($port < 1 || $port > 65535) {
            $port = 9232;
        }

        $this->username = $username;
        $this->password = $password;
        $this->host = $host;
        $this->port = $port;
        $this->httpClient = $httpClient;
    }

    /**
     * @param string  $command
     * @param mixed[] $parameters
     *
     * @return NodeResponse
     * @throws NodeException
     */
    public function __call(string $command, $parameters): NodeResponse
    {
        return $this->executeCommand($command, ...$parameters);
    }

    /**
     * @return AccountsSubClient
     */
    public function accounts(): AccountsSubClient
    {
        return $this->getSubClient(AccountsSubClient::class);
    }

    /**
     * @return BlockChainSubClient
     */
    public function blockChain(): BlockChainSubClient
    {
        return $this->getSubClient(BlockChainSubClient::class);
    }

    /**
     * @return ControlSubClient
     */
    public function control(): ControlSubClient
    {
        return $this->getSubClient(ControlSubClient::class);
    }

    /**
     * @param string  $command
     * @param mixed[] ...$parameters
     *
     * @return NodeResponse
     */
    public function executeCommand(string $command, ...$parameters): NodeResponse
    {
        $request = new Request(
            'POST',
            "{$this->host}:{$this->port}",
            [
                'Content-type' => 'application/json',
                'Authorization' => 'Basic ' . base64_encode("{$this->username}:{$this->password}")
            ],
            json_encode([
                'method' => $command,
                'params' => array_filter(array_values($parameters), function($param) {
                    return $param !== null;
                }),
                'id' => uniqid('request_id_', true)
            ])
        );

        try {
            $response = $this->httpClient->send($request);
        } catch (ClientException $exception) {
            $response = $exception->getResponse();
        } catch (ServerException $exception) {
            $response = $exception->getResponse();
        }

        return NodeResponse::fromPsrResponse($response);
    }

    /**
     * @param string $command
     *
     * @return string
     * @throws NodeException
     */
    public function help(string $command = null): string
    {
        $response = $this->executeCommand('help', $command)
            ->throwIfUnsuccessful();

        return $response->getBody();
    }

    /**
     * @param string $address
     *
     * @return bool
     */
    public function validateAddressFormat(string $address): bool
    {
        return preg_match('/^[Gg][a-km-zA-HJ-NP-Z1-9]{25,34}$/', $address) === 1;
    }

    /**
     * @return bool
     * @throws NodeException
     */
    public function verifyConnection(): bool
    {
        return $this->executeCommand('getinfo')->getStatusCode() === NodeResponse::STATUS_SUCCESS;
    }

    /**
     * @return WalletSubClient
     */
    public function wallet(): WalletSubClient
    {
        return $this->getSubClient(WalletSubClient::class);
    }

    /**
     * @param string $subClient
     *
     * @return mixed
     */
    private function getSubClient(string $subClient)
    {
        if (!($this->subClients[$subClient] ?? null) instanceof $subClient) {
            $this->subClients[$subClient] = $subClient::fromClient($this);
        }

        return $this->subClients[$subClient];
    }
}
