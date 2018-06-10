<?php
declare(strict_types=1);

namespace Trejjam\ThePay;

use Tp;

class MerchantConfig extends Tp\MerchantConfig
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
