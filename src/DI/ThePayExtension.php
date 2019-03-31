<?php declare(strict_types = 1);

namespace Contributte\ThePay\DI;

use Contributte\ThePay\Helper\DataApi;
use Contributte\ThePay\Helper\IDivMerchant;
use Contributte\ThePay\Helper\IRadioMerchant;
use Contributte\ThePay\IPayment;
use Contributte\ThePay\IPermanentPayment;
use Contributte\ThePay\IReturnedPayment;
use Contributte\ThePay\MerchantConfig;
use Nette;
use Nette\DI\CompilerExtension;
use Nette\DI\Config\Expect;
use Nette\DI\ContainerBuilder;
use Nette\Utils\Validators;

/**
 * @property ExtensionConfiguration $config pre Nette 3.0 compatibility
 */
class ThePayExtension extends CompilerExtension
{

	/**
	 * pre Nette 3.0 compatibility
	 *
	 * @var ExtensionConfiguration
	 */
	private $shadowConfig;

	public function __construct()
	{
		$this->config = new ExtensionConfiguration();

		if (!method_exists(get_parent_class($this), 'getConfigSchema')) {
			// pre Nette 3.0 compatibility
			$this->shadowConfig = $this->config;
			$this->config = [];
		}
	}

	public function getConfigSchema(): Nette\DI\Config\Schema
	{
		return Expect::from($this->config)->normalize(
			function (array $config) {
				if ($config['demo'] === true) {
					$this->config->setDemoMerchant();
					$config['merchant'] = (array) $this->config->merchant;
				}

				return $config;
			}
		);
	}

	public function loadConfiguration(): void
	{
		if (!method_exists(get_parent_class($this), 'getConfigSchema')) {
			// pre Nette 3.0 compatibility

			$config = (array) $this->shadowConfig;
			$config['demo'] = true; // Backward compatibility
			$config['merchant'] = (array) $this->shadowConfig->merchant;

			$this->validateConfig($config);

			Validators::assertField($this->config, 'demo', 'bool');

			if ($this->config['demo'] === true) {
				$this->shadowConfig->setDemoMerchant();
				$this->config['merchant'] = (array) $this->shadowConfig->merchant;
			}

			Validators::assertField($this->config, 'merchant', 'array');
			Validators::assertField($this->config['merchant'], 'gateUrl', 'string');
			Validators::assertField($this->config['merchant'], 'merchantId', 'int');
			Validators::assertField($this->config['merchant'], 'accountId', 'int');
			Validators::assertField($this->config['merchant'], 'password', 'string');
			Validators::assertField($this->config['merchant'], 'dataApiPassword', 'string');
			Validators::assertField($this->config['merchant'], 'webServicesWsdl', 'string');
			Validators::assertField($this->config['merchant'], 'dataWebServicesWsdl', 'string');

			$this->config = (object) $this->config;
			$this->config->merchant = (object) $this->config->merchant;
		}
	}

	public function beforeCompile(): void
	{
		parent::beforeCompile();

		$merchantConfig = $this->config->merchant;

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
					$this->config->demo,
					$merchantConfig->gateUrl,
					$merchantConfig->merchantId,
					$merchantConfig->accountId,
					$merchantConfig->password,
					$merchantConfig->dataApiPassword,
					$merchantConfig->webServicesWsdl,
					$merchantConfig->dataWebServicesWsdl,
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
