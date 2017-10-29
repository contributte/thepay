<?php
declare(strict_types=1);

namespace Trejjam\ThePay;

use Tp;

/**
 * Configuration class for the ThePay component.
 * Modify properties in this class to contain valid data for your
 * account. This data you can find in the ThePay administration interface.
 */
class MerchantConfig extends Tp\MerchantConfig
{
	public $isDemo = FALSE;

	public function isDemo() : bool
	{
		return $this->isDemo;
	}
}
