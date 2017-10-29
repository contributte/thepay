<?php
declare(strict_types=1);

namespace Trejjam\ThePay;

interface IPermanentPayment
{
	/**
	 * @return PermanentPayment
	 */
	function create();
}
