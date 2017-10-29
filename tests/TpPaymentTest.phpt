<?php
declare(strict_types=1);

namespace Test;

use Nette;
use Tester;
use Tester\Assert;
use Trejjam;

$container = require_once __DIR__ . '/bootstrap.php';

class TpPaymentTest extends Tester\TestCase
{
	/**
	 * @var Nette\Application\LinkGenerator
	 */
	protected $linkGenerator;
	/**
	 * @var Nette\DI\Container
	 */
	private $container;

	function __construct(Nette\DI\Container $container)
	{

		$this->container = $container;
	}

	public function setUp()
	{
		$this->linkGenerator = $this->container->getByType('Nette\Application\LinkGenerator');
	}

	protected function createConfig()
	{
		return new Trejjam\ThePay\MerchantConfig();
	}


	/**
	 * @return Trejjam\ThePay\Payment
	 * @throws Nette\Application\UI\InvalidLinkException
	 * @throws \Tp\InvalidParameterException
	 */
	protected function createPayment()
	{
		$payment = new Trejjam\ThePay\Payment($this->createConfig(), $this->linkGenerator);

		$payment->setMethodId('21');
		$payment->setValue('2000.23');
		$payment->setCurrency('CZK');
		$payment->setMerchantData('24');
		$payment->setMerchantSpecificSymbol('10');
		$payment->setReturnUrl('http://google.com');
		$payment->setBackToEshopUrl('http://google.com');

		return $payment;
	}

	public function testGenerateUrl()
	{
		$payment = $this->createPayment();

		$queryArgs = $payment->getArgs();
		$queryArgs['signature'] = $payment->getSignature();

		Assert::same('https://www.thepay.cz/demo-gate/?merchantId=1&accountId=1&value=2000.23&currency=CZK&merchantData=24&returnUrl=http%3A%2F%2Fgoogle.com&backToEshopUrl=http%3A%2F%2Fgoogle.com&methodId=21&merchantSpecificSymbol=10&signature=2baf4a3c6274180ae619b7520d66f97f', $payment->getRedirectUrl());
	}

	public function testGenerateUrl2()
	{
		$payment = $this->createPayment();

		$payment->setValue('23.79');

		$queryArgs = $payment->getArgs();
		$queryArgs['signature'] = $payment->getSignature();

		Assert::same('https://www.thepay.cz/demo-gate/?merchantId=1&accountId=1&value=23.79&currency=CZK&merchantData=24&returnUrl=http%3A%2F%2Fgoogle.com&backToEshopUrl=http%3A%2F%2Fgoogle.com&methodId=21&merchantSpecificSymbol=10&signature=84f06619bb9b039757f0254db9178e9c', $payment->getRedirectUrl());
	}
}

$test = new TpPaymentTest($container);
$test->run();
