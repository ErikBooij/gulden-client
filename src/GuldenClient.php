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
use Prophecy\Exception\InvalidArgumentException;
use Throwable;

class GuldenClient
{
    static private $alphabet = '123456789ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnopqrstuvwxyz';

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
                'Content-type'  => 'application/json',
                'Authorization' => 'Basic ' . base64_encode("{$this->username}:{$this->password}"),
            ],
            json_encode([
                'method' => $command,
                'params' => array_filter(array_values($parameters), function ($param) {
                    return $param !== null;
                }),
                'id'     => uniqid('request_id_', true),
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
    public function validateAddress(string $address): bool
    {
        if (!$this->validateAddressFormat($address)) {
            return false;
        }

        try {
            $decoded = $this->base58decode($address);

            $d1 = hash('sha256', substr($decoded, 0, 21), true);
            $d2 = hash('sha256', $d1, true);

            if (substr_compare($decoded, $d2, 21, 4)) {
                return false;
            }

            return true;
        } catch (Throwable $throwable) {
            return false;
        }
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

    private function base58decode(string $input): string
    {
        // Borrowed from https://rosettacode.org/wiki/Bitcoin/address_validation#PHP
        $out = array_fill(0, 25, 0);

        for ($i = 0; $i < strlen($input); $i++) {
            if (($p = strpos(self::$alphabet, $input[$i])) === false) {
                throw new InvalidArgumentException("Invalid character ({$input[$i]}) found");
            }

            $c = $p;

            for ($j = 25; $j--;) {
                $c += (int)(58 * $out[$j]);
                $out[$j] = (int)($c % 256);
                $c /= 256;
                $c = (int)$c;
            }

            if ($c != 0) {
                throw new InvalidArgumentException('Address is too long');
            }
        }

        $result = '';

        foreach ($out as $val) {
            $result .= chr($val);
        }

        return $result;
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
