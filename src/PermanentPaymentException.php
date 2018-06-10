<?php
declare(strict_types=1);

namespace Trejjam\ThePay;

use Trejjam;

class PermanentPaymentException extends \RuntimeException
{
	public const UNDEFINED_PROPERTY = 1;
}
