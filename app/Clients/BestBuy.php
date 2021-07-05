<?php

namespace App\Clients;

use App\Stock;
use Illuminate\Support\Facades\Http;

class BestBuy implements Client
{
    public function checkAvailability(Stock $stock): StockStatus //This function checks the availability of a Stock
    {
        $results = Http::get($this->endpoint($stock->sku))->json(); //Uses Http package and get function. As an input the
        //sku is passed and it is gotten as json format which contains all the information provided by the webpage.

        return new StockStatus(
            $results['onlineAvailability'],
            $this->dollarsToCents($results['salePrice']) //In the case of Best Buy the field referred to Availability is
            //called 'onlineAvailability' and it is extracted, then the same goes for 'salePrice'.

        );

    }

    protected function endpoint($sku): string
    {
        $key = config('services.clients.bestBuy.key');
        return "https://api.bestbuy.com/v1/products/{$sku}.json?apiKey={$key}";
    }

    protected function dollarsToCents($salePrice)
    {
        return (int) ($salePrice * 100);
    }
}
