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
			],
		]
	);
	$compiler->processExtensions();

	$thePayConfig = $thePayExtension->getConfig();

	Assert::same(true, $thePayConfig->demo);
	Assert::same('https://www.thepay.cz/demo-gate/', $thePayConfig->merchant->gateUrl);
	Assert::same(1, $thePayConfig->merchant->merchantId);
	Assert::same(1, $thePayConfig->merchant->accountId);
	Assert::same('my$up3rsecr3tp4$$word', $thePayConfig->merchant->password);
	Assert::same('my$up3rsecr3tp4$$word', $thePayConfig->merchant->dataApiPassword);
	Assert::same('https://www.thepay.cz/demo-gate/api/gate-api-demo.wsdl', $thePayConfig->merchant->webServicesWsdl);
	Assert::same('https://www.thepay.cz/demo-gate/api/data-demo.wsdl', $thePayConfig->merchant->dataWebServicesWsdl);
});

Toolkit::test(function (): void {
	$thePayExtension = new ThePayExtension();

	$compiler = new Compiler();
	$compiler->addExtension('thepay', $thePayExtension);
	$compiler->addConfig(
		[
			'thepay' => [
				'demo' => false,
				'merchant' => [
					'merchantId' => 10,
					'accountId' => 42,
					'password' => 'abc',
					'dataApiPassword' => 'def',
				],
			],
		]
	);
	$compiler->processExtensions();

	$thePayConfig = $thePayExtension->getConfig();

	Assert::same(false, $thePayConfig->demo);
	Assert::same('https://www.thepay.cz/gate/', $thePayConfig->merchant->gateUrl);
	Assert::same(10, $thePayConfig->merchant->merchantId);
	Assert::same(42, $thePayConfig->merchant->accountId);
	Assert::same('abc', $thePayConfig->merchant->password);
	Assert::same('def', $thePayConfig->merchant->dataApiPassword);
	Assert::same('https://www.thepay.cz/gate/api/gate-api.wsdl', $thePayConfig->merchant->webServicesWsdl);
	Assert::same('https://www.thepay.cz/gate/api/data.wsdl', $thePayConfig->merchant->dataWebServicesWsdl);
});
