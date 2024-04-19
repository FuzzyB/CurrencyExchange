<?php

declare(strict_types=1);

namespace src\ExchangeModule\Domain\Rate;

use src\ExchangeModule\Domain\Markup\NullMarkup;
use src\ExchangeModule\Exceptions\CurrencyArgumentException;
use src\ExchangeModule\ValueObject\Currency;

class RateResolver
{
    private const EXCHANGE_RATES = [
        'EUR_GBP' =>  1.5678,
        'GBP_EUR' => 1.5432,
    ];

    /**
     * @throws CurrencyArgumentException
     */
    public function getDesiredCurrencyRate(Currency $from, Currency $destinationCurrency): Rate
    {
        $key = $from->getSymbol().'_'.$destinationCurrency->getSymbol();

        if (!isset(self::EXCHANGE_RATES[$key])) {
            throw new CurrencyArgumentException('Exchange rate is not available.');
        }

        return new Rate(self::EXCHANGE_RATES[$key], new NullMarkup(), $destinationCurrency );
    }

    public static function getExchangeRates(): array
    {
        return self::EXCHANGE_RATES;
    }

}