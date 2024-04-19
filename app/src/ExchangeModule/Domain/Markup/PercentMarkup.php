<?php

namespace src\ExchangeModule\Domain\Markup;

use src\ExchangeModule\Domain\Rate\Rate;
use src\ExchangeModule\ValueObject\Money;

class PercentMarkup extends AbstractMarkup
{
    public function __construct(private readonly float $percent)
    {
    }

    public function applyTo(Rate $rate): Rate
    {
        $currentRate = $rate->getRateAmount();

        return new Rate($currentRate, $this, $rate->getCurrency());
    }

    public function calculateMarkup(Money $amount): Money
    {
        return $amount->multiply($this->percent/100);
    }

}