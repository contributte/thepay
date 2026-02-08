<?php declare(strict_types = 1);

namespace Tests\Cases;

use Contributte\Tester\Toolkit;
use Contributte\ThePay\MerchantConfig;
use Contributte\ThePay\Payment;
use Nette\Application\LinkGenerator;
use Nette\Application\Routers\RouteList;
use Nette\Http\UrlScript;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

Toolkit::test(function (): void {
	$router = new RouteList();
	$router->addRoute('<presenter>[/<action>[/<id>]]', 'Homepage:default');
	$linkGenerator = new LinkGenerator($router, new UrlScript('http://localhost.tld/'));

	$payment = new Payment(new MerchantConfig(), $linkGenerator);
	$payment->setMethodId(21);
	$payment->setValue(2000.23);
	$payment->setCurrency('CZK');
	$payment->setMerchantData('24');
	$payment->setMerchantSpecificSymbol('10');
	$payment->setReturnUrl('http://google.com');
	$payment->setBackToEshopUrl('http://google.com');

	Assert::same('https://www.thepay.cz/demo-gate/?merchantId=1&accountId=1&value=2000.23&currency=CZK&merchantData=24&returnUrl=http%3A%2F%2Fgoogle.com&backToEshopUrl=http%3A%2F%2Fgoogle.com&methodId=21&merchantSpecificSymbol=10&signature=2baf4a3c6274180ae619b7520d66f97f', $payment->getRedirectUrl());
});

Toolkit::test(function (): void {
	$router = new RouteList();
	$router->addRoute('<presenter>[/<action>[/<id>]]', 'Homepage:default');
	$linkGenerator = new LinkGenerator($router, new UrlScript('http://localhost.tld/'));

	$payment = new Payment(new MerchantConfig(), $linkGenerator);
	$payment->setMethodId(21);
	$payment->setValue(23.79);
	$payment->setCurrency('CZK');
	$payment->setMerchantData('24');
	$payment->setMerchantSpecificSymbol('10');
	$payment->setReturnUrl('http://google.com');
	$payment->setBackToEshopUrl('http://google.com');

	Assert::same('https://www.thepay.cz/demo-gate/?merchantId=1&accountId=1&value=23.79&currency=CZK&merchantData=24&returnUrl=http%3A%2F%2Fgoogle.com&backToEshopUrl=http%3A%2F%2Fgoogle.com&methodId=21&merchantSpecificSymbol=10&signature=84f06619bb9b039757f0254db9178e9c', $payment->getRedirectUrl());
});
