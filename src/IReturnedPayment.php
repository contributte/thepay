<?php declare(strict_types = 1);

namespace Contributte\ThePay;

interface IReturnedPayment
{

	public function create(): ReturnedPayment;

}
