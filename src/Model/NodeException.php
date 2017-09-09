<?php
declare(strict_types=1);

namespace GuldenPHP\Model;

use Exception;

class NodeException extends Exception
{
    /**
     * @param string $errorMessage
     *
     * @return NodeException
     */
    public static function withMessage(string $errorMessage): self
    {
        $exception = new static($errorMessage);

        return $exception;
    }
}
