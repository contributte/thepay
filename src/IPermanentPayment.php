<?php
declare(strict_types=1);

namespace Trejjam\ThePay;

interface IPermanentPayment
{
	public function create() : PermanentPayment;
}
