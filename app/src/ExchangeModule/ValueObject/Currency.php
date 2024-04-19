<?php

declare(strict_types=1);

namespace src\ExchangeModule\ValueObject;

use InvalidArgumentException;

class Currency
{
    private static array $currencies = [
        'EUR' => [
            'currency_code' => 'EUR',
        ],
        'GBP' => [
            'currency_code' => 'GBP',
        ]
    ];

    public function __construct(private string $currencyCode)
    {
        $this->currencyCode = mb_strtoupper($this->currencyCode);

        if (!isset(self::$currencies[$this->currencyCode])) {
            throw new InvalidArgumentException('Unsupported currency used: ' . $this->currencyCode);
        }
    }

    public function getSymbol(): string
    {
        return $this->currencyCode;
    }
}