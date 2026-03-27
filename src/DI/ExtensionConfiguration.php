<?php declare(strict_types = 1);

namespace Contributte\ThePay\DI;

final class ExtensionConfiguration
{

	public function __construct(
		public string $merchantId = '',
		public int $projectId = 0,
		public string $apiPassword = '',
		public string $apiUrl = 'https://api.thepay.cz/',
		public string $gateUrl = 'https://gate.thepay.cz/',
		public string $language = 'cs',
		public bool $demo = false,
	)
	{
	}

	public function setDemo(): void
	{
		$this->apiUrl = 'https://demo.api.thepay.cz/';
		$this->gateUrl = 'https://demo.gate.thepay.cz/';
	}

}
