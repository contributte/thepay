<?php
/**
 * Created by PhpStorm.
 * User: Jan
 * Date: 26. 10. 2014
 * Time: 17:38
 */

namespace Trejjam\ThePay\DI;

use Nette,
	Tp,
	Trejjam;

class ThePayExtension extends Trejjam\BaseExtension\DI\BaseExtension
{
	protected $default = [
		'demo'     => TRUE,
		'merchant' => [
			'gateUrl'             => 'https://www.thepay.cz/gate/',
			'merchantId'          => '',
			'accountId'           => '',
			'password'            => '',
			'dataApiPassword'     => '',
			'webServicesWsdl'     => 'https://www.thepay.cz/gate/api/api.wsdl',
			'dataWebServicesWsdl' => 'https://www.thepay.cz/gate/api/data.wsdl',
		],
	];

	protected $merchantDemo = [
		'gateUrl'             => 'https://www.thepay.cz/demo-gate/',
		'merchantId'          => '1',
		'accountId'           => '1',
		'password'            => 'my$up3rsecr3tp4$$word',
		'dataApiPassword'     => 'my$up3rsecr3tp4$$word',
		'webServicesWsdl'     => 'https://www.thepay.cz/demo-gate/api/api-demo.wsdl',
		'dataWebServicesWsdl' => 'https://www.thepay.cz/demo-gate/api/data-demo.wsdl',
	];

	protected $classesDefinition = [
		'merchantConfig' => 'Tp\MerchantConfig',
		'helper.dataApi' => 'Trejjam\ThePay\Helper\DataApi',
	];

	protected $factoriesDefinition = [
		'paymentFactory'              => 'Trejjam\ThePay\IPayment',
		'permanentPaymentFactory'     => 'Trejjam\ThePay\IPermanentPayment',
		'returnedPaymentFactory'      => 'Trejjam\ThePay\IReturnedPayment',
		'helper.radioMerchantFactory' => 'Trejjam\ThePay\Helper\IRadioMerchant',
	];

	public function beforeCompile()
	{
		parent::beforeCompile();

		$config = $this->createConfig();
		$merchantConfig = $config['merchant'];

		$classes = $this->getClasses();

		$classes['merchantConfig']
			->addSetup(
				'$service->gateUrl = ?;' . "\n" .
				'$service->merchantId = ?;' . "\n" .
				'$service->accountId = ?;' . "\n" .
				'$service->password = ?;' . "\n" .
				'$service->dataApiPassword = ?;' . "\n" .
				'$service->webServicesWsdl = ?;' . "\n" .
				'$service->dataWebServicesWsdl = ?',
				[
					$merchantConfig['gateUrl'],
					$merchantConfig['merchantId'],
					$merchantConfig['accountId'],
					$merchantConfig['password'],
					$merchantConfig['dataApiPassword'],
					$merchantConfig['webServicesWsdl'],
					$merchantConfig['dataWebServicesWsdl'],
				]);
	}

	protected function createConfig()
	{
		$config = parent::createConfig();
		if ($config['demo']) {
			$config['merchant'] = $this->merchantDemo;
		}

		return $config;
	}
}
