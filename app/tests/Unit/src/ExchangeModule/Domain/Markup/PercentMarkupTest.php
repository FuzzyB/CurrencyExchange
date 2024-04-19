<?php
declare(strict_types=1);

namespace src\ExchangeModule;

use PHPUnit\Framework\TestCase;
use src\ExchangeModule\Domain\Markup\PercentMarkup;
use src\ExchangeModule\Domain\Rate\Rate;
use src\ExchangeModule\ValueObject\Currency;
use src\ExchangeModule\ValueObject\Money;

require __DIR__ . "/../../../../../../autoload.php";

class PercentMarkupTest extends TestCase
{

    public function testApplyTo(): void
    {
        $percentMarkup = new PercentMarkup(1);
        $destinationCurrency = new Currency('EUR');
        $rate = new Rate(1.23, $percentMarkup, $destinationCurrency);

        $result = $percentMarkup->applyTo($rate);

        self::assertInstanceOf(Rate::class, $result);
        self::assertSame($result->getCurrency(), $destinationCurrency);
        self::assertEquals($result->getRateAmount(), $rate->getRateAmount());
    }

    public function testCalculateMarkup(): void
    {
        $money = new Money(123456, new Currency('EUR'));
        $percentMarkup = new PercentMarkup(1);
        $result = $percentMarkup->calculateMarkup($money);

        self::assertInstanceOf(Money::class, $result);
        self::assertSame($result->getCurrency(), $money->getCurrency());
        self::assertEquals(1235, $result->getAmount());
    }
}