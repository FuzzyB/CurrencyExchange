<?php
declare(strict_types=1);

namespace src\ExchangeModule;

require __DIR__ . "/../../../../autoload.php";

use InvalidArgumentException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use src\ExchangeModule\Domain\Enum\OperationType;
use src\ExchangeModule\Domain\Markup\NullMarkup;
use src\ExchangeModule\Domain\Markup\PercentMarkup;
use src\ExchangeModule\Domain\Rate\Rate;
use src\ExchangeModule\Domain\Rate\RateResolver;
use src\ExchangeModule\ValueObject\Currency;
use src\ExchangeModule\ValueObject\Money;

class ExchangeServiceTest extends TestCase
{
    private MockObject|RateResolver $rateResolver;
    private PercentMarkup|MockObject $markup;

    public function setUp(): void
    {
        $this->markup = self::createMock(PercentMarkup::class);
        $this->rateResolver = self::createMock(RateResolver::class);
    }

    /**
     * @dataProvider successDataProvider
     */
    public function testSuccessTransaction($amount, $rateAmount, $markupPercent, $tradeCurrencyCode, $destinationCurrencyCode, $operationType, $expectedResult): void
    {
        $tradeCurrency = new Currency($tradeCurrencyCode);
        $destinationCurrency = new Currency($destinationCurrencyCode);
        $tradingRate = new Rate($rateAmount, new NullMarkup(), $destinationCurrency);
        $tradingRateWithMarkup = new Rate($rateAmount, new PercentMarkup($markupPercent), $destinationCurrency);

        $tradeMoneyEuro = new Money($amount, $tradeCurrency);

        $this->rateResolver->expects(self::once())
            ->method('getDesiredCurrencyRate')
            ->with($tradeCurrency, $destinationCurrency)
            ->willReturn($tradingRate);

        $this->markup->expects(self::once())
            ->method('applyTo')
            ->with($tradingRate)
            ->willReturn($tradingRateWithMarkup)
        ;

        $service = new ExchangeService(
            $this->rateResolver,
            $this->markup
        );

        $resultAmount = $service->transaction($tradeMoneyEuro, $destinationCurrency, $operationType); // = 156.78 GBP + 1% = 158.3478//service is buying while client is selling

        self::assertEquals(new Money($expectedResult, $destinationCurrency), $resultAmount);
    }

    private function successDataProvider(): array
    {
        return [
            [1000000, 1.5432, 1,  'GBP', 'EUR', OperationType::Sell, 1527768], // (100 * 1.5432) - 1% = 152.7768:: client is buying while service is selling
            [1000000, 1.5432, 1, 'GBP', 'EUR', OperationType::Buy, 1558632], // (100 * 1.5432) + 1% = 155.8632 :: client is selling while service is buying
            [1000000, 1.5678, 1, 'EUR', 'GBP', OperationType::Sell, 1552122], // (100 * 1.5678) - 1% = 155.2122 :: client is buying while service is selling
            [1000000, 1.5678, 1, 'EUR', 'GBP', OperationType::Buy, 1583478], // (100 * 1.5678) + 1% = 15.83478 :: client is selling while service is buying
        ];
    }

    private function failDataProvider(): array
    {
        return [
            [1000000, 1.5678, 1, 'PLN', 'GBP', OperationType::Sell, 1552122], // (100 * 1.5678) - 1% = 155.2122 :: client is buying while service is selling
            [1000000, 1.5678, 1, 'EUR', 'PLN', OperationType::Buy, 1583478], // (100 * 1.5678) + 1% = 15.83478 :: client is selling while service is buying
        ];
    }

    /**
     * @dataProvider failDataProvider
     */
    public function testFailedCurrencyTransaction($amount, $rateAmount, $markupPercent, $tradeCurrencyCode, $destinationCurrencyCode, $operationType, $expectedResult): void
    {
        self::expectExceptionObject(new InvalidArgumentException('Unsupported currency used: '));

        $tradeCurrency = new Currency($tradeCurrencyCode);
        $destinationCurrency = new Currency($destinationCurrencyCode);
        $tradeMoneyEuro = new Money($amount, $tradeCurrency);

        $this->rateResolver->expects(self::once())
            ->method('getDesiredCurrencyRate')
            ->with($tradeCurrency, $destinationCurrency);

        $service = new ExchangeService(
            $this->rateResolver,
            $this->markup
        );

        $service->transaction($tradeMoneyEuro, $destinationCurrency, $operationType);
    }
}
