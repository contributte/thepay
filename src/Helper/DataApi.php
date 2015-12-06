<?php
/**
 * Created by PhpStorm.
 * User: jam
 * Date: 24.9.15
 * Time: 11:08
 */

namespace Trejjam\ThePay\Helper;

use Nette,
	Trejjam,
	Tp;

class DataApi
{
	/**
	 * @var Tp\MerchantConfig
	 */
	protected $config;

	function __construct(Trejjam\ThePay\MerchantConfig $config)
	{
		$this->config = $config;
	}

	/**
	 * @return Trejjam\ThePay\MerchantConfig
	 */
	public function getMerchantConfig()
	{
		return $this->config;
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
	 * @param Tp\DataApi\MerchantAccountMethod $method
	 * @param string(tight|209x127|86x86)      $type
	 * @return null|string
	 */
	public function getPaymentMethodIcon(Tp\DataApi\MerchantAccountMethod $method, $type = 'tight')
	{
		return Nette\Utils\Strings::replace($this->config->gateUrl, [
			'~/demo-~' => '/',
		]) . 'images/logos/public/' . $type . '/' . $method->getId() . '.png';
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
