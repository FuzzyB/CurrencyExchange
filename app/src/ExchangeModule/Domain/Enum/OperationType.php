<?php

declare(strict_types=1);

namespace src\ExchangeModule\Domain\Enum;

enum OperationType: string
{
    case Sell = 'sell';
    case Buy = 'buy';
}