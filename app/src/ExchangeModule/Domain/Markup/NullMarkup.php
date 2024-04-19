<?php

namespace src\ExchangeModule\Domain\Markup;

use src\ExchangeModule\Domain\Rate\Rate;
use src\ExchangeModule\ValueObject\Money;

class NullMarkup extends AbstractMarkup
{
    public function applyTo(Rate $rate): Rate
    {
        return $rate;
    }

    public function calculateMarkup(Money $amount): Money
    {
        return $amount;
    }
}