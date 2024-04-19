<?php

declare(strict_types=1);

namespace src\ExchangeModule\Domain\Rate;

use src\ExchangeModule\Domain\Enum\OperationType;
use src\ExchangeModule\Domain\Markup\AbstractMarkup;
use src\ExchangeModule\ValueObject\Currency;
use src\ExchangeModule\ValueObject\Money;

class Rate implements RateInterface
{
    public function __construct(
        private readonly float $rateAmount,
        private readonly AbstractMarkup $markup,
        private readonly Currency $currency
    ) {

    }

    public function applyTo(Money $money, OperationType $operationType): Money
    {
        $newAmount = $this->calculate($money, $operationType);

        return new Money($newAmount->getAmount(), $this->currency);
    }

    public function getRateAmount(): float
    {
        return $this->rateAmount;
    }

    private function calculate(Money $money, OperationType $operationType): Money
    {
        $ratedAmount = $money->multiply($this->getRateAmount());
        $markupAmount = $this->markup->calculateMarkup($ratedAmount);

        if ($operationType === OperationType::Buy) {
            return $ratedAmount->add($markupAmount);
        } else {
            return $ratedAmount->subtract($markupAmount);
        }
    }

    public function getCurrency(): Currency
    {
        return $this->currency;
    }
}