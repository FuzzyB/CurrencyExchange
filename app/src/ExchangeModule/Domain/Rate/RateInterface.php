<?php

declare(strict_types=1);

namespace src\ExchangeModule\Domain\Rate;

use src\ExchangeModule\Domain\Enum\OperationType;
use src\ExchangeModule\ValueObject\Money;

interface RateInterface
{
    public function applyTo(Money $money, OperationType $operationType): Money;

    public function getRateAmount(): float;
}