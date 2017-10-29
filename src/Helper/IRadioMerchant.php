<?php
declare(strict_types=1);

namespace Trejjam\ThePay\Helper;

use Tp\Helper\RadioMerchant;

interface IRadioMerchant
{
	/**
	 * @return RadioMerchant
	 */
	function create();
}
