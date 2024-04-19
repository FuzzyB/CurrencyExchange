<?php
declare(strict_types=1);

namespace src\ExchangeModule;

require __DIR__ . "/../../../../../../autoload.php";

use PHPUnit\Framework\TestCase;
use src\ExchangeModule\Domain\Rate\Rate;
use src\ExchangeModule\Domain\Rate\RateResolver;
use src\ExchangeModule\Exceptions\CurrencyArgumentException;
use src\ExchangeModule\ValueObject\Currency as Currency;

class RateResolverTest extends TestCase
{
    public function testSuccessGetDesiredCurrencyRate()
    {
        $destinationCurrency = new Currency('EUR');
        $tradeCurrency = new Currency('GBP');
        $resolver = new RateResolver();
        $rate = $resolver->getDesiredCurrencyRate($tradeCurrency, $destinationCurrency);

        self::assertInstanceOf(Rate::class, $rate);

    }

    public function testMissingRateForCurrencies()
    {
        self::expectExceptionObject(new CurrencyArgumentException('Exchange rate is not available.'));

        $destinationCurrency = new Currency('GBP');
        $tradeCurrency = new Currency('GBP');
        $resolver = new RateResolver();
        $resolver->getDesiredCurrencyRate($tradeCurrency, $destinationCurrency);
    }

    public function testReturnsRateObject()
    {
        $tradeCurrency = new Currency('GBP');
        $destinationCurrency = new Currency('EUR');
        $resolver = new RateResolver();
        $rate = $resolver->getDesiredCurrencyRate($tradeCurrency, $destinationCurrency);

        self::assertEquals($rate->getRateAmount(), 1.5432);
    }

}