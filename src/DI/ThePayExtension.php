<?php declare(strict_types = 1);

namespace Contributte\ThePay\DI;

use Contributte\ThePay\Helper\DataApi;
use Contributte\ThePay\Helper\IDivMerchant;
use Contributte\ThePay\Helper\IRadioMerchant;
use Contributte\ThePay\IPayment;
use Contributte\ThePay\IPermanentPayment;
use Contributte\ThePay\IReturnedPayment;
use Contributte\ThePay\MerchantConfig;
use Nette\DI\CompilerExtension;
use Nette\DI\ContainerBuilder;
use Nette\Utils\Validators;

class ThePayExtension extends CompilerExtension
{

	/** @var mixed[] */
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

	/** @var mixed[] */
	protected $merchantDemo = [
		'gateUrl'             => 'https://www.thepay.cz/demo-gate/',
		'merchantId'          => 1,
		'accountId'           => 1,
		'password'            => 'my$up3rsecr3tp4$$word',
		'dataApiPassword'     => 'my$up3rsecr3tp4$$word',
		'webServicesWsdl'     => 'https://www.thepay.cz/demo-gate/api/gate-api-demo.wsdl',
		'dataWebServicesWsdl' => 'https://www.thepay.cz/demo-gate/api/data-demo.wsdl',
	];

	public function loadConfiguration(): void
	{
		$this->validateConfig($this->default);

		Validators::assertField($this->config, 'demo', 'bool');

		if ($this->config['demo'] === true) {
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

	public function beforeCompile(): void
	{
		parent::beforeCompile();

		$merchantConfig = $this->config['merchant'];

		$builder = $this->getContainerBuilder();

		$merchantConfigDefinition = $builder->addDefinition($this->prefix('merchantConfig'))->setType(MerchantConfig::class);
		$builder->addDefinition($this->prefix('helper.dataApi'))->setType(DataApi::class);

		$this->registerFactory($builder, $this->prefix('paymentFactory'), IPayment::class);
		$this->registerFactory($builder, $this->prefix('permanentPaymentFactory'), IPermanentPayment::class);
		$this->registerFactory($builder, $this->prefix('returnedPaymentFactory'), IReturnedPayment::class);
		$this->registerFactory($builder, $this->prefix('helper.radioMerchantFactory'), IRadioMerchant::class);
		$this->registerFactory($builder, $this->prefix('helper.divMerchantFactory'), IDivMerchant::class);

		$merchantConfigDefinition
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

	private function registerFactory(ContainerBuilder $builder, string $name, string $interface): void
	{
		if (method_exists($builder, 'addFactoryDefinition')) {
			$builder->addFactoryDefinition($name)->setImplement($interface);
		} else {
			$builder->addDefinition($name)->setImplement($interface);
		}
	}

}
