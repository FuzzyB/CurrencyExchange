<?php
declare(strict_types=1);

namespace src\ExchangeModule;

use PHPUnit\Framework\TestCase;
use src\ExchangeModule\ValueObject\Currency;

require __DIR__ . "/../../../../../autoload.php";

class CurrencyTest extends TestCase
{
    public function testGetSymbol()
    {
        $currency = new Currency('EUR');

        self::assertEquals($currency->getSymbol(), 'EUR');
    }

    public function testGetSymbolError()
    {
        self::expectExceptionObject(new \InvalidArgumentException('Unsupported currency used:'));

        $currency = new Currency('PLN');
    }
}