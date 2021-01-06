<?php
require __DIR__ . '/../../vendor/autoload.php';
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Slim\Views\PhpRenderer;
use Classes\Forex;
use Classes\GoRest;

$app = AppFactory::create();

$app->get('/product-stats', function (Request $request, Response $response, $args) {
	$goRest = new GoRest();
	$productStats = $goRest->setProductsStatistics()->calculateFinalPrice();
	$forex = new Forex();
	$rates = $forex->_rates;

	$phpView = new PhpRenderer('../views/', ["title" => "App", "rates" => $rates]);
	$phpView->setLayout("layout.php");

	return $phpView->render(
		$response,
		"products.php",
		[
			'title' => 'Products',
			'stats' => $productStats
		]
	);

	return $response;
});

$app->post('/product-stats', function (Request $request, Response $response) {

	$currency =	isset($_POST['currency']) ? $_POST['currency'] : 'USD';
	$goRest = new GoRest();

	$productStats = $goRest->setProductsStatistics()->calculateFinalPrice();

	$forex = new Forex();
	$rates = $forex->_rates;

	foreach ($productStats  as &$product) {
		foreach ($product as &$stat) {
			$stat =	is_numeric($stat) ? $forex->exchange($stat, $currency) : $stat;
		}
	}

	$phpView = new PhpRenderer('../views/', ["title" => "App", "rates" => $rates]);
	$phpView->setLayout("layout.php");

	return $phpView->render(
		$response,
		"products.php",
		[
			'title' => 'Products',
			'stats' => $productStats
		]
	);
	return $response;
});

$app->run();
