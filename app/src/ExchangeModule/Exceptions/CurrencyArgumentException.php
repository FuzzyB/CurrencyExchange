<?php
declare(strict_types=1);

namespace src\ExchangeModule\Exceptions;

class CurrencyArgumentException extends \Exception
{
    public function __construct(string $message = 'Invalid Argument Exception', int $code = 0)
    {
        parent::__construct($message, $code);
    }
}