<?php
declare(strict_types=1);

namespace src\ExchangeModule\ValueObject;

use PHPUnit\Util\Exception;

class Money
{
    public function __construct(
        private readonly int $amount,
        private readonly Currency $currency,
    ) {}

    public function getCurrency(): Currency
    {
        return $this->currency;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function multiply(float $multiplyAmount): self
    {
        return new Money((int)round($this->amount * $multiplyAmount, 0, PHP_ROUND_HALF_UP), $this->currency);
    }

    public function add(Money $increase): self
    {
        return new Money($this->amount + $increase->getAmount(), $this->currency);
    }

    public function subtract(Money $decrease): self
    {
        $result = $this->amount - $decrease->getAmount();
        if ($result < 0) {
            throw new Exception('Subtract result have to be positive');
        }

        return new Money($this->amount - $decrease->getAmount(), $this->currency);
    }

    public function __toString(): string
    {
        return number_format($this->amount/10000, 2, ',', ' ') . $this->currency->getSymbol();
    }
}