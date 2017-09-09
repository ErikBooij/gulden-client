<?php
declare(strict_types=1);

namespace GuldenPHP\Model;

use Psr\Http\Message\ResponseInterface;

class NodeResponse
{
    /** @var int */
    const STATUS_SUCCESS = 200;

    /** @var mixed */
    private $body = [];

    /** @var string */
    private $error = '';

    /** @var ResponseInterface */
    private $originalResponse = null;

    /** @var int */
    private $statusCode = -1;

    /**
     * @param int               $statusCode
     * @param mixed             $body
     * @param ResponseInterface $originalResponse
     * @param string            $error
     */
    private function __construct(
        int $statusCode,
        $body,
        ResponseInterface $originalResponse = null,
        string $error = ''
    ) {
        $this->statusCode = $statusCode;
        $this->body = $body;
        $this->originalResponse = $originalResponse;
        $this->error = $error;
    }

    /**
     * @param ResponseInterface $response
     *
     * @return NodeResponse
     */
    public static function fromPsrResponse(ResponseInterface $response): self
    {
        $error = '';
        $body = json_decode($response->getBody()->getContents(), true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            $body = [];
        }

        if (isset($body['error']) && is_array($body['error'])) {
            $error = $body['error']['message'] ?? '';
        }

        return new static(
            $response->getStatusCode(),
            $body['result'] ?? [],
            $response,
            $error
        );
    }

    /**
     * @param mixed $default
     *
     * @return mixed
     */
    public function getBody($default = null)
    {
        return !empty($this->body) ? $this->body : $default;
    }

    /**
     * @return string
     */
    public function getError(): string
    {
        return $this->error;
    }

    /**
     * @return ResponseInterface
     */
    public function getOriginalResponse(): ResponseInterface
    {
        return $this->originalResponse;
    }

    /**
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * @return bool
     */
    public function isSuccessful(): bool
    {
        return $this->error === '' && $this->getStatusCode() === static::STATUS_SUCCESS;
    }

    /**
     * @return NodeResponse
     * @throws NodeException
     */
    public function throwIfUnsuccessful(): NodeResponse
    {
        if (!$this->isSuccessful()) {
            throw NodeException::withMessage($this->error);
        }

        return $this;
    }
}
