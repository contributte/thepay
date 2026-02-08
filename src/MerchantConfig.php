<?php declare(strict_types = 1);

namespace Contributte\ThePay;

use Tp\MerchantConfig as TpMerchantConfig;

class MerchantConfig extends TpMerchantConfig
{

	public bool $isDemo = false;

	public string $resourceUrl = 'https://www.thepay.cz/gate';

	public function isDemo(): bool
	{
		return $this->isDemo;
	}

}
