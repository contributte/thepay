<?php declare(strict_types = 1);

namespace Tests\Cases;

use Contributte\Tester\Environment;
use Contributte\Tester\Toolkit;
use Contributte\ThePay\DI\ThePayExtension;
use Nette\DI\Compiler;
use Nette\DI\Container;
use Nette\DI\ContainerLoader;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Tester\Assert;
use Tests\Fixtures\PsrStubFactory;
use ThePay\ApiClient\Service\ApiServiceInterface;
use ThePay\ApiClient\Service\GateServiceInterface;
use ThePay\ApiClient\Service\SignatureService;
use ThePay\ApiClient\TheClient;
use ThePay\ApiClient\TheConfig;

require __DIR__ . '/../bootstrap.php';

Toolkit::test(function (): void {
	$loader = new ContainerLoader(Environment::getTestDir());

	$class = $loader->load(function (Compiler $compiler): void {
		$compiler->addExtension('thepay', new ThePayExtension());
		$compiler->addConfig([
			'thepay' => [
				'demo' => true,
				'merchantId' => 'test-merchant-id',
				'projectId' => 3,
				'apiPassword' => 'secret',
			],
			'services' => [
				'httpClient' => [
					'type' => ClientInterface::class,
					'factory' => PsrStubFactory::class . '::createHttpClient',
				],
				'requestFactory' => [
					'type' => RequestFactoryInterface::class,
					'factory' => PsrStubFactory::class . '::createRequestFactory',
				],
				'streamFactory' => [
					'type' => StreamFactoryInterface::class,
					'factory' => PsrStubFactory::class . '::createStreamFactory',
				],
			],
		]);
	}, 'thepay-container-test');

	/** @var Container $container */
	$container = new $class();

	Assert::type(TheConfig::class, $container->getByType(TheConfig::class));
	Assert::type(SignatureService::class, $container->getByType(SignatureService::class));
	Assert::type(ApiServiceInterface::class, $container->getByType(ApiServiceInterface::class));
	Assert::type(GateServiceInterface::class, $container->getByType(GateServiceInterface::class));
	Assert::type(TheClient::class, $container->getByType(TheClient::class));
});
