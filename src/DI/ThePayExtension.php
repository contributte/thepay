<?php
declare(strict_types=1);

namespace Trejjam\ThePay\DI;

use Nette\Utils\Validators;
use Trejjam;

class ThePayExtension extends Trejjam\BaseExtension\DI\BaseExtension
{
	protected $default = [
		'demo'     => true,
		'merchant' => [
			'gateUrl'             => 'https://www.thepay.cz/gate/',
			'merchantId'          => null,
			'accountId'           => null,
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
		'merchantConfig' => Trejjam\ThePay\MerchantConfig::class,
		'helper.dataApi' => Trejjam\ThePay\Helper\DataApi::class,
	];

	protected $factoriesDefinition = [
		'paymentFactory'              => Trejjam\ThePay\IPayment::class,
		'permanentPaymentFactory'     => Trejjam\ThePay\IPermanentPayment::class,
		'returnedPaymentFactory'      => Trejjam\ThePay\IReturnedPayment::class,
		'helper.radioMerchantFactory' => Trejjam\ThePay\Helper\IRadioMerchant::class,
	];

	public function loadConfiguration(bool $validateConfig = true) : void
	{
		parent::loadConfiguration();

		Validators::assertField($this->config, 'demo', 'bool');

		if ($this->config['demo']) {
			$this->config['merchant'] = $this->merchantDemo;
		}

		Validators::assertField($this->config, 'merchant', 'array');
		Validators::assertField($this->config['merchant'], 'gateUrl', 'string');
		Validators::assertField($this->config['merchant'], 'merchantId', 'int');
		Validators::assertField($this->config['merchant'], 'accountId', 'int');
		Validators::assertField($this->config['merchant'], 'password', 'string');
		Validators::assertField($this->config['merchant'], 'dataApiPassword', 'string');
		Validators::assertField($this->config['merchant'], 'webServicesWsdl', 'string');
		Validators::assertField($this->config['merchant'], 'dataWebServicesWsdl', 'string');
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
					$merchantConfig['merchantId'],
					$merchantConfig['accountId'],
					$merchantConfig['password'],
					$merchantConfig['dataApiPassword'],
					$merchantConfig['webServicesWsdl'],
					$merchantConfig['dataWebServicesWsdl'],
				]
			);
	}
}
