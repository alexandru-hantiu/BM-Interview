<?php

namespace Classes;

use Exception;
use GuzzleHttp\Client;

class Forex
{
    private $_url = "https://api.currencyfreaks.com/latest?apikey=477b4e5483474e12bbe1de48dd63f704";

    public $_rates = null;


    public function __construct()
    {
        $this->_rates  = $this->getRates();
        $this->_rates->USD = 1;
    }

    public function getRates()
    {
        try {
            $guzzleCLient = new Client();
            $callResponse = $guzzleCLient->request('GET', $this->_url)->getBody()->getContents();
        } catch (Exception $e) {
            echo "Caught exception: ", $e->getMessage();
        }
        return json_decode($callResponse)->rates;
    }

    public function exchange($amount, $currency)
    {
        $exchangedAmount = $amount * $this->_rates->$currency;
        return $exchangedAmount;
    }
}
