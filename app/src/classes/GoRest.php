<?php

namespace Classes;

use Exception;
use GuzzleHttp\Client;

class GoRest
{
    private $_url = 'https://gorest.co.in/public-api/products';

    protected $_statistics = array(
        'name',
        'description',
        'price',
        'discount_amount'
    );

    protected $_productsStatistics =  array();

    function __construct()
    {
        $this->products = $this->getProducts();
    }

    public function getProducts()
    {
        try {
            $guzzleCLient = new Client();
            $callResponse = $guzzleCLient->request('GET', $this->_url)->getBody()->getContents();
        } catch (Exception $e) {
            echo "Caught exception: ", $e->getMessage();
        }
        return json_decode($callResponse)->data;
    }

    public function setProductsStatistics()
    {
        $tempStatistics = [];
        foreach ($this->products as $product) {
            $sanitizedResult = $this->sanitizeCallResult($product);
            array_push($tempStatistics, $sanitizedResult);
        }
        $this->_productsStatistics = $tempStatistics;
        return $this;
    }

    public function sanitizeCallResult($product)
    {
        $formatedStatistics = [];
        foreach ($this->_statistics as $statistic) {
            $formatedStatistics[$statistic] = property_exists($product, $statistic) ?  $product->$statistic : null;
        }
        return $formatedStatistics;
    }

    public function calculateFinalPrice()
    {
        foreach ($this->_productsStatistics as &$statistic) {
            $finalPrice = $statistic['price'] - $statistic['discount_amount'];
            $statistic['final_price'] = $finalPrice;
        }
        return $this->_productsStatistics;
    }
}
