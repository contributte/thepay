<?php
/**
 * Created by PhpStorm.
 * User: jam
 * Date: 19.8.15
 * Time: 16:13
 */

namespace Trejjam\ThePay;

use Trejjam;

class PermanentPaymentException extends \RuntimeException
{
	const
		UNDEFINED_PROPERTY = 1;
}
