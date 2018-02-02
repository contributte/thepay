<?php
declare(strict_types=1);

namespace Trejjam\ThePay;

use Tp;

class MerchantConfig extends Tp\MerchantConfig
{
	public $isDemo = FALSE;

	public function isDemo() : bool
	{
		return $this->isDemo;
	}
}
