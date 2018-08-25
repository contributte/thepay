<?php
declare(strict_types=1);

namespace Contributte\ThePay;

interface IPayment
{
	public function create() : Payment;
}
