<?php
declare(strict_types=1);

namespace Trejjam\ThePay;

interface IPayment
{
	public function create() : Payment;
}
