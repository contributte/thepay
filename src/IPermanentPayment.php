<?php
declare(strict_types=1);

namespace Contributte\ThePay;

interface IPermanentPayment
{
	public function create() : PermanentPayment;
}
