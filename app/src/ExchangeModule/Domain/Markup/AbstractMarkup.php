<?php

namespace src\ExchangeModule\Domain\Markup;

use src\ExchangeModule\Domain\Rate\Rate;
use src\ExchangeModule\ValueObject\Money;

abstract class AbstractMarkup
{
    abstract public function applyTo(Rate $rate): Rate;

    public abstract function calculateMarkup(Money $amount): Money;

}