<?php
declare(strict_types=1);

namespace Trejjam\ThePay;

interface IReturnedPayment
{
	/**
	 * @return ReturnedPayment
	 */
	function create();
}
