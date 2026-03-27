<?php declare(strict_types = 1);

namespace Tests\Cases;

use Contributte\Tester\Toolkit;
use Contributte\ThePay\DI\ThePayExtension;
use Nette\DI\Compiler;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

Toolkit::test(function (): void {
	$thePayExtension = new ThePayExtension();

	$compiler = new Compiler();
	$compiler->addExtension('thepay', $thePayExtension);
	$compiler->addConfig(
		[
			'thepay' => [
				'demo' => true,
				'merchantId' => 'test-merchant-id',
				'projectId' => 3,
				'apiPassword' => 'secret',
			],
		]
	);
	$compiler->processExtensions();

	$thePayConfig = $thePayExtension->getConfig();

	Assert::same(true, $thePayConfig->demo);
	Assert::same('test-merchant-id', $thePayConfig->merchantId);
	Assert::same(3, $thePayConfig->projectId);
	Assert::same('secret', $thePayConfig->apiPassword);
	Assert::same('https://demo.api.thepay.cz/', $thePayConfig->apiUrl);
	Assert::same('https://demo.gate.thepay.cz/', $thePayConfig->gateUrl);
	Assert::same('cs', $thePayConfig->language);
});

Toolkit::test(function (): void {
	$thePayExtension = new ThePayExtension();

	$compiler = new Compiler();
	$compiler->addExtension('thepay', $thePayExtension);
	$compiler->addConfig(
		[
			'thepay' => [
				'demo' => false,
				'merchantId' => 'prod-merchant-id',
				'projectId' => 10,
				'apiPassword' => 'prod-secret',
			],
		]
	);
	$compiler->processExtensions();

	$thePayConfig = $thePayExtension->getConfig();

	Assert::same(false, $thePayConfig->demo);
	Assert::same('prod-merchant-id', $thePayConfig->merchantId);
	Assert::same(10, $thePayConfig->projectId);
	Assert::same('prod-secret', $thePayConfig->apiPassword);
	Assert::same('https://api.thepay.cz/', $thePayConfig->apiUrl);
	Assert::same('https://gate.thepay.cz/', $thePayConfig->gateUrl);
	Assert::same('cs', $thePayConfig->language);
});

Toolkit::test(function (): void {
	$thePayExtension = new ThePayExtension();

	$compiler = new Compiler();
	$compiler->addExtension('thepay', $thePayExtension);
	$compiler->addConfig(
		[
			'thepay' => [
				'merchantId' => 'custom-merchant',
				'projectId' => 5,
				'apiPassword' => 'pass',
				'apiUrl' => 'https://custom.api.thepay.cz/',
				'gateUrl' => 'https://custom.gate.thepay.cz/',
				'language' => 'en',
			],
		]
	);
	$compiler->processExtensions();

	$thePayConfig = $thePayExtension->getConfig();

	Assert::same(false, $thePayConfig->demo);
	Assert::same('custom-merchant', $thePayConfig->merchantId);
	Assert::same(5, $thePayConfig->projectId);
	Assert::same('pass', $thePayConfig->apiPassword);
	Assert::same('https://custom.api.thepay.cz/', $thePayConfig->apiUrl);
	Assert::same('https://custom.gate.thepay.cz/', $thePayConfig->gateUrl);
	Assert::same('en', $thePayConfig->language);
});
