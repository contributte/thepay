<?php declare(strict_types = 1);

namespace Contributte\ThePay\Helper;

use Tp\Helper\RadioMerchant;

interface IRadioMerchant
{

	public function create(): RadioMerchant;

}
