<?php
declare(strict_types=1);

namespace Trejjam\ThePay\DI;

use Trejjam;

class ThePayExtension extends Trejjam\BaseExtension\DI\BaseExtension
{
	protected $default = [
		'demo'     => true,
		'merchant' => [
			'gateUrl'             => 'https://www.thepay.cz/gate/',
			'merchantId'          => '',
			'accountId'           => '',
			'password'            => '',
			'dataApiPassword'     => '',
			'webServicesWsdl'     => 'https://www.thepay.cz/gate/api/gate-api.wsdl',
			'dataWebServicesWsdl' => 'https://www.thepay.cz/gate/api/data.wsdl',
		],
	];

	protected $merchantDemo = [
		'gateUrl'             => 'https://www.thepay.cz/demo-gate/',
		'merchantId'          => 1,
		'accountId'           => 1,
		'password'            => 'my$up3rsecr3tp4$$word',
		'dataApiPassword'     => 'my$up3rsecr3tp4$$word',
		'webServicesWsdl'     => 'https://www.thepay.cz/demo-gate/api/gate-api-demo.wsdl',
		'dataWebServicesWsdl' => 'https://www.thepay.cz/demo-gate/api/data-demo.wsdl',
	];

	protected $classesDefinition = [
		'merchantConfig' => 'Trejjam\ThePay\MerchantConfig',
		'helper.dataApi' => 'Trejjam\ThePay\Helper\DataApi',
	];

	protected $factoriesDefinition = [
		'paymentFactory'              => 'Trejjam\ThePay\IPayment',
		'permanentPaymentFactory'     => 'Trejjam\ThePay\IPermanentPayment',
		'returnedPaymentFactory'      => 'Trejjam\ThePay\IReturnedPayment',
		'helper.radioMerchantFactory' => 'Trejjam\ThePay\Helper\IRadioMerchant',
	];

	public function loadConfiguration(bool $validateConfig = true) : void
	{
		parent::loadConfiguration();

		if ($this->config['demo']) {
			$this->config['merchant'] = $this->merchantDemo;
		}
	}

	public function beforeCompile() : void
	{
		parent::beforeCompile();

		$merchantConfig = $this->config['merchant'];

		$types = $this->getTypes();

		$types['merchantConfig']
			->addSetup(
				'$service->isDemo = ?;' . "\n" .
				'$service->gateUrl = ?;' . "\n" .
				'$service->merchantId = ?;' . "\n" .
				'$service->accountId = ?;' . "\n" .
				'$service->password = ?;' . "\n" .
				'$service->dataApiPassword = ?;' . "\n" .
				'$service->webServicesWsdl = ?;' . "\n" .
				'$service->dataWebServicesWsdl = ?',
				[
					$this->config['demo'],
					$merchantConfig['gateUrl'],
					intval($merchantConfig['merchantId']),
					intval($merchantConfig['accountId']),
					$merchantConfig['password'],
					$merchantConfig['dataApiPassword'],
					$merchantConfig['webServicesWsdl'],
					$merchantConfig['dataWebServicesWsdl'],
				]
			);
	}
}
