<?php declare(strict_types = 1);

namespace Contributte\ThePay\DI;

use Nette\DI\CompilerExtension;
use Nette\Schema\Expect;
use Nette\Schema\Schema;
use ThePay\ApiClient\Service\ApiService;
use ThePay\ApiClient\Service\ApiServiceInterface;
use ThePay\ApiClient\Service\GateService;
use ThePay\ApiClient\Service\GateServiceInterface;
use ThePay\ApiClient\Service\SignatureService;
use ThePay\ApiClient\TheClient;
use ThePay\ApiClient\TheConfig;

class ThePayExtension extends CompilerExtension
{

	/** @var ExtensionConfiguration */
	protected $config; // phpcs:ignore SlevomatCodingStandard.TypeHints.PropertyTypeHint.MissingNativeTypeHint

	public function __construct()
	{
		$this->config = new ExtensionConfiguration();
	}

	public function getConfigSchema(): Schema
	{
		return Expect::from($this->config)->before(
			function (array $config): array {
				if (($config['demo'] ?? false) === true) {
					$this->config->setDemo();
					$config['apiUrl'] = $this->config->apiUrl;
					$config['gateUrl'] = $this->config->gateUrl;
				}

				return $config;
			}
		);
	}

	public function beforeCompile(): void
	{
		parent::beforeCompile();

		$builder = $this->getContainerBuilder();

		$builder->addDefinition($this->prefix('config'))
			->setType(TheConfig::class)
			->setArguments([
				$this->config->merchantId,
				$this->config->projectId,
				$this->config->apiPassword,
				$this->config->apiUrl,
				$this->config->gateUrl,
				$this->config->language,
			]);

		$builder->addDefinition($this->prefix('signatureService'))
			->setType(SignatureService::class);

		$builder->addDefinition($this->prefix('apiService'))
			->setType(ApiServiceInterface::class)
			->setFactory(ApiService::class);

		$builder->addDefinition($this->prefix('gateService'))
			->setType(GateServiceInterface::class)
			->setFactory(GateService::class);

		$builder->addDefinition($this->prefix('client'))
			->setType(TheClient::class);
	}

}
