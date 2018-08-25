<?php
declare(strict_types=1);

namespace Contributte\ThePay;

use Tp\MerchantConfig as TpMerchantConfig;

class MerchantConfig extends TpMerchantConfig
{
	/**
	 * @var bool
	 */
	public $isDemo = false;
	/**
	 * @var string
	 */
	public $resourceUrl = 'https://www.thepay.cz/gate';

	public function isDemo() : bool
	{
		return $this->isDemo;
	}
}
