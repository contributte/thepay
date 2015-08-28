<?php
/**
 * Created by PhpStorm.
 * User: Jan
 * Date: 26. 10. 2014
 * Time: 17:38
 */

namespace Trejjam\ThePay\DI;

use Nette,
	Tp;

class ThePayExtension extends Nette\DI\CompilerExtension
{
	protected $defaults = [
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

	public function loadConfiguration()
	{
		parent::loadConfiguration();

		$builder = $this->getContainerBuilder();
		$config = $this->createConfig();

		$classesDefinition = [
			'merchantConfig'       => 'Tp\MerchantConfig',
		];
		$factoriesDefinition = [
			'paymentFactory'              => 'Trejjam\ThePay\IPayment',
			'permanentPaymentFactory'     => 'Trejjam\ThePay\IPermanentPayment',
			'returnedPaymentFactory'      => 'Trejjam\ThePay\IReturnedPayment',
			'helper.radioMerchantFactory' => 'Trejjam\ThePay\Helper\IRadioMerchant',
		];

		/** @var Nette\DI\ServiceDefinition[] $classes */
		$classes = [];

		foreach ($classesDefinition as $k => $v) {
			$classes[$k] = $builder->addDefinition($this->prefix($k))
								   ->setClass($v);
		}

		/** @var Nette\DI\ServiceDefinition[] $factories */
		$factories = [];

		foreach ($factoriesDefinition as $k => $v) {
			$factories[$k] = $builder->addDefinition($this->prefix($k))
									 ->setImplement($v);
		}
	}

	public function beforeCompile()
	{
		parent::beforeCompile();

		$builder = $this->getContainerBuilder();
		$config = $this->createConfig();
		$merchantConfig = $config['merchant'];

		$builder->getDefinition($this->prefix('merchantConfig'))
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
		$config = $this->getConfig($this->defaults);
		Nette\Utils\Validators::assert($config, 'array');
		if ($config['demo']) {
			$config['merchant'] = $this->merchantDemo;
		}

		return $config;
	}
}
