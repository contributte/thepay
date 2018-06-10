<?php
declare(strict_types=1);

namespace Trejjam\ThePay;

interface IReturnedPayment
{
	public function create() : ReturnedPayment;
}
