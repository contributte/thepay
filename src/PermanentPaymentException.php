<?php
declare(strict_types=1);

namespace Contributte\ThePay;

class PermanentPaymentException extends \RuntimeException
{
	public const UNDEFINED_PROPERTY = 1;
}
