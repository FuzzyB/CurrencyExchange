<?php
declare(strict_types=1);

namespace src\ExchangeModule;

use PHPUnit\Framework\TestCase;
use src\ExchangeModule\ValueObject\Currency;
use src\ExchangeModule\ValueObject\Money;

require __DIR__ . "/../../../../../autoload.php";

class MoneyTest  extends TestCase
{
    public function testMultiply()
    {
        $a = 2234234234; // 223423.4234
        $multiply = 3.4;
        $money = new Money($a, new Currency('EUR'));

        $result = $money->multiply($multiply);

        self::assertInstanceOf(Money::class, $result);
        self::assertEquals(round($a*$multiply, 0, PHP_ROUND_HALF_UP), $result->getAmount());
        self::assertEquals(new Currency('EUR'), $result->getCurrency());
    }

    public function testAdd()
    {
        $currency = new Currency('EUR');
        $baseAmount = 2234234234; // 223423.4234
        $increase = 1112134; // 111.2134
        $baseMoney = new Money($baseAmount, $currency);
        $increaseMoney = new Money($increase, $currency);
        $result = $baseMoney->add($increaseMoney);

        self::assertInstanceOf(Money::class, $result);
        self::assertEquals($baseAmount + $increase, $result->getAmount());
        self::assertEquals(new Currency('EUR'), $result->getCurrency());
    }

    public function testSubtract()
    {
        $currency = new Currency('EUR');
        $baseAmount = 2234234234; // 223423.4234
        $subtractAmount = 1112134; // 111.2134
        $baseMoney = new Money($baseAmount, $currency);
        $increaseMoney = new Money($subtractAmount, $currency);
        $result = $baseMoney->subtract($increaseMoney);

        self::assertInstanceOf(Money::class, $result);
        self::assertEquals($baseAmount - $subtractAmount, $result->getAmount());
        self::assertEquals(new Currency('EUR'), $result->getCurrency());
    }

    public function testSubtractFailed()
    {
        self::expectExceptionMessage('Subtract result have to be positive');

        $currency = new Currency('EUR');
        $baseAmount = 2234234234;
        $subtractAmount = 999991112134;
        $baseMoney = new Money($baseAmount, $currency);
        $increaseMoney = new Money($subtractAmount, $currency);
        $baseMoney->subtract($increaseMoney);
    }


}