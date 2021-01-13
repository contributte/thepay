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
use Nette\Schema\Expect;
use Nette\Schema\Schema;

class ThePayExtension extends CompilerExtension
{

	/** @var ExtensionConfiguration */
	protected $config;

	public function __construct()
	{
		$this->config = new ExtensionConfiguration();
	}

	public function getConfigSchema(): Schema
	{
		return Expect::from($this->config)->before(
			function (array $config): array {
				if ($config['demo'] === true) {
					$this->config->setDemoMerchant();
					$config['merchant'] = (array) $this->config->merchant;
				}

				return $config;
			}
		);
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
		$builder->addFactoryDefinition($name)->setImplement($interface);
	}

}
