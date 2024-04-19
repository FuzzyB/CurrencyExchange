<?php
declare(strict_types=1);

namespace src\ExchangeModule;

use src\ExchangeModule\Domain\Enum\OperationType;
use src\ExchangeModule\Domain\Markup\AbstractMarkup;
use src\ExchangeModule\Domain\Rate\RateResolver;
use src\ExchangeModule\ValueObject\Currency;
use src\ExchangeModule\ValueObject\Money;

class ExchangeService {

    public function __construct(
        private readonly RateResolver $rateResolver,
        private readonly AbstractMarkup $markup,
    ) {}

    /**
     * @throws Exceptions\CurrencyArgumentException
     */
    public function transaction(Money $tradeMoneyEuro, Currency $destinationCurrency, OperationType $operationType): Money
    {
        $currencyRate = $this->rateResolver->getDesiredCurrencyRate($tradeMoneyEuro->getCurrency(), $destinationCurrency);
        $processingRate = $this->markup->applyTo($currencyRate);

        return $processingRate->applyTo($tradeMoneyEuro, $operationType);
    }

}
