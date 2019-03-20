<?php declare(strict_types = 1);

namespace Contributte\ThePay\Helper;

use Tp\Helper\DivMerchant;
use Tp\Payment;

interface IDivMerchant
{

	public function create(Payment $payment): DivMerchant;

}
