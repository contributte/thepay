<?php
/**
 * Created by PhpStorm.
 * User: jam
 * Date: 24.9.15
 * Time: 11:08
 */

namespace Trejjam\ThePay\Helper;

use Nette,
	App,
	Trejjam,
	Tp;

class DataApi
{
	protected $config;

	function __construct(Tp\MerchantConfig $config)
	{
		$this->config = $config;
	}
	
	/**
	 * @param bool $onlyActive
	 * @return Tp\DataApi\GetPaymentMethodsResponse
	 */
	public function getPaymentMethods($onlyActive = TRUE)
	{
		return Tp\Helper\DataApi::getPaymentMethods($this->config, $onlyActive);
	}

	/**
	 * @param string $paymentId
	 * @return Tp\DataApi\GetPaymentResponse
	 */
	public function getPayment($paymentId)
	{
		return Tp\Helper\DataApi::getPayment($this->config, $paymentId);
	}

	/**
	 * @param string $paymentId
	 * @return Tp\DataApi\GetPaymentInstructionsResponse
	 */
	public function getPaymentInstructions($paymentId)
	{
		return Tp\Helper\DataApi::getPaymentInstructions($this->config, $paymentId);
	}

	/**
	 * @param string $paymentId
	 * @return Tp\DataApi\GetPaymentStateResponse
	 */
	public function getPaymentState($paymentId)
	{
		return Tp\Helper\DataApi::getPaymentState($this->config, $paymentId);
	}
}
