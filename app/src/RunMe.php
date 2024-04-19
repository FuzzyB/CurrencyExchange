<?php
declare(strict_types=1);

namespace src;

use src\ExchangeModule\Domain\Enum\OperationType;
use src\ExchangeModule\Domain\Markup\PercentMarkup;
use src\ExchangeModule\Domain\Rate\RateResolver;
use src\ExchangeModule\ExchangeService;
use src\ExchangeModule\ValueObject\Currency;
use src\ExchangeModule\ValueObject\Money;

require dirname(__DIR__) . "/autoload.php";

class RunMe {
    public function execute(): void
    {
        $markupPercent = 1;
        $euro = new Currency('EUR');
        $gbp = new Currency('GBP');
        $tradeEuro = new Money(1000000, $euro);
        $tradeGbp = new Money(1000000, $gbp);
        $rateResolver = new RateResolver();
        $app = new ExchangeService($rateResolver, new PercentMarkup($markupPercent));


        $this->printRates(RateResolver::getExchangeRates());
        $this->printMarkup($markupPercent);
        $this->logResult($app->transaction($tradeEuro, $gbp, OperationType::Buy), OperationType::Buy, $tradeEuro);
        $this->logResult($app->transaction($tradeGbp, $euro, OperationType::Sell), OperationType::Sell, $tradeGbp);
        $this->logResult($app->transaction($tradeGbp, $euro, OperationType::Buy), OperationType::Buy, $tradeGbp);
        $this->logResult($app->transaction($tradeEuro, $gbp, OperationType::Sell), OperationType::Sell, $tradeEuro);
    }

    private function logResult(Money $result, OperationType $operation, $sells): void
    {
        echo "Customer " .$this->translateOperationForCustomer($operation). " " .$sells ." and take ". $result.'<br>';
    }

    private function translateOperationForCustomer(OperationType $operationType): string
    {
        return $operationType === OperationType::Buy ? 'Sell' : 'Buy';
    }

    private function printRates(array $rates): void
    {
        echo "Current rates:<br>";
        foreach ($rates as $key => $rate) {
            echo $key.': '.$rate.'<br>';
        }
        echo '<br>';
    }

    private function printMarkup(int $markupPercent)
    {
        echo "Markup percent: $markupPercent<br>";
        echo '<br>';
    }

}

