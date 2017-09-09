<?php
declare(strict_types=1);

namespace GuldenPHP\Model;

use Exception;

class InputException extends Exception
{
    /**
     * @param string $message
     *
     * @return InputException
     */
    public static function withMessage(string $message): self
    {
        return new static($message);
    }
}
