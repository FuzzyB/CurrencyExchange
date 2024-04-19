<?php
declare(strict_types=1);

namespace src\ExchangeModule;

require __DIR__ . "/../../../../../../autoload.php";

use PHPUnit\Framework\TestCase;
use src\ExchangeModule\Domain\Enum\OperationType;
use src\ExchangeModule\Domain\Markup\PercentMarkup;
use src\ExchangeModule\Domain\Rate\Rate;
use src\ExchangeModule\ValueObject\Currency;
use src\ExchangeModule\ValueObject\Money;

class RateTest extends TestCase
{
    private PercentMarkup|\PHPUnit\Framework\MockObject\MockObject $markup;

    protected function setUp(): void
    {
        parent::setUp();
        $this->markup = self::CreateMock(PercentMarkup::class);
    }

    /**
     *@dataProvider testData
     */
    public function testApplyToBuy($operationType, $expected)
    {
        $euro = new Currency('EUR');
        $money = new Money(234233, $euro);
        $rate = new Rate(2.3, $this->markup, $euro);
        $ratedMoney = (int) round(234233*2.3, 0, PHP_ROUND_HALF_UP);
        $markupAmount = 122;

        $this->markup->expects(self::once())
            ->method('calculateMarkup')
            ->with(new Money($ratedMoney, $euro))
            ->willReturn(new Money($markupAmount, $euro));

        $result = $rate->applyTo($money, $operationType);

        self::assertInstanceOf(Money::class, $result);
        self::assertEquals($expected, $result->getAmount());
    }

    private function testData(): array
    {
        return [
            [OperationType::Buy, 538858],
            [OperationType::Sell, 538614],
        ];
    }
}