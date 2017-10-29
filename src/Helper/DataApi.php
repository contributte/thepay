<?php
declare(strict_types=1);

namespace Trejjam\ThePay\Helper;

use Nette;
use Trejjam;
use Tp;
use Tp\DataApi\Parameters;

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

	public function getMerchantConfig() : Trejjam\ThePay\MerchantConfig
	{
		return $this->config;
	}

	public function getPaymentMethods(bool $onlyActive = TRUE) : Tp\DataApi\Responses\GetPaymentMethodsResponse
	{
		return Tp\Helper\DataApi::getPaymentMethods($this->config, $onlyActive);
	}

	/**
	 * @param Tp\DataApi\Parameters\MerchantAccountMethod $method
	 * @param string(tight|209x127|86x86)                 $type
	 *
	 * @return null|string
	 */
	public function getPaymentMethodIcon(Parameters\MerchantAccountMethod $method, string $type = 'tight') : string
	{
		return Nette\Utils\Strings::replace($this->config->gateUrl, [
				'~/demo-~' => '/',
			]) . 'images/logos/public/' . $type . '/' . $method->getId() . '.png';
	}

	public function getPayment(string $paymentId) : Tp\DataApi\Responses\GetPaymentResponse
	{
		return Tp\Helper\DataApi::getPayment($this->config, $paymentId);
	}

	public function getPaymentInstructions(string $paymentId) : Tp\DataApi\Responses\GetPaymentInstructionsResponse
	{
		return Tp\Helper\DataApi::getPaymentInstructions($this->config, $paymentId);
	}

	public function getPaymentState(string $paymentId) : Tp\DataApi\Responses\GetPaymentStateResponse
	{
		return Tp\Helper\DataApi::getPaymentState($this->config, $paymentId);
	}

	public function getPayments(
		Parameters\GetPaymentsSearchParams $searchParams = NULL,
		Parameters\PaginationRequest $pagination = NULL,
		Parameters\Ordering $ordering = NULL
	) : Tp\DataApi\Responses\GetPaymentsResponse {
		return Tp\Helper\DataApi::getPayments($this->config, $searchParams, $pagination, $ordering);
	}

	/**
	 * @param       $type
	 * @param array $paymentMethods
	 *
	 * @return Tp\DataApi\Responses\SetPaymentMethodsResponse
	 * @throws Tp\InvalidSignatureException
	 * @throws Tp\SoapException
	 */
	public function setPaymentMethods(
		$type,
		array $paymentMethods = NULL
	) : Tp\DataApi\Responses\SetPaymentMethodsResponse {
		return Tp\Helper\DataApi::setPaymentMethods($this->config, $type, $paymentMethods);
	}
}
