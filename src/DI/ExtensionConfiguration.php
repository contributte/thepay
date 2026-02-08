<?php declare(strict_types = 1);

namespace Contributte\ThePay\DI;

final class ExtensionConfiguration
{

	public function __construct(
		public bool $demo = false,
		public MerchantConfiguration $merchant = new MerchantConfiguration(),
	)
	{
	}

	public function setDemoMerchant(): void
	{
		$this->merchant->gateUrl = 'https://www.thepay.cz/demo-gate/';
		$this->merchant->merchantId = 1;
		$this->merchant->accountId = 1;
		$this->merchant->password = 'my$up3rsecr3tp4$$word';
		$this->merchant->dataApiPassword = 'my$up3rsecr3tp4$$word';
		$this->merchant->webServicesWsdl = 'https://www.thepay.cz/demo-gate/api/gate-api-demo.wsdl';
		$this->merchant->dataWebServicesWsdl = 'https://www.thepay.cz/demo-gate/api/data-demo.wsdl';
	}

}
