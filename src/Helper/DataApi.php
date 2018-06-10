<?php
declare(strict_types=1);

namespace Trejjam\ThePay\Helper;

use Tp;
use Tp\DataApi\Parameters;
use Trejjam;

class DataApi
{
	/**
	 * @var Trejjam\ThePay\MerchantConfig
	 */
	protected $config;

	public function __construct(Trejjam\ThePay\MerchantConfig $config)
	{
		$this->config = $config;
	}

	public function getMerchantConfig() : Trejjam\ThePay\MerchantConfig
	{
		return $this->config;
	}

	public function getPaymentMethods(bool $onlyActive = true) : Tp\DataApi\Responses\GetPaymentMethodsResponse
	{
		return Tp\Helper\DataApi::getPaymentMethods($this->config, $onlyActive);
	}

	/**
	 * @param string $type (tight|209x127|86x86)
	 */
	public function getPaymentMethodIcon(Parameters\MerchantAccountMethod $method, string $type = 'tight') : string
	{
		return "{$this->config->resourceUrl}/images/logos/public/{$type}/{$method->getId()}.png";
	}

	public function getPayment(int $paymentId) : Tp\DataApi\Responses\GetPaymentResponse
	{
		return Tp\Helper\DataApi::getPayment($this->config, $paymentId);
	}

	public function getPaymentInstructions(int $paymentId) : Tp\DataApi\Responses\GetPaymentInstructionsResponse
	{
		return Tp\Helper\DataApi::getPaymentInstructions($this->config, $paymentId);
	}

	public function getPaymentState(int $paymentId) : Tp\DataApi\Responses\GetPaymentStateResponse
	{
		return Tp\Helper\DataApi::getPaymentState($this->config, $paymentId);
	}

	public function getPayments(
		?Parameters\GetPaymentsSearchParams $searchParams = null,
		?Parameters\PaginationRequest $pagination = null,
		?Parameters\Ordering $ordering = null
	) : Tp\DataApi\Responses\GetPaymentsResponse {
		return Tp\Helper\DataApi::getPayments($this->config, $searchParams, $pagination, $ordering);
	}

	/**
	 * @param mixed $type
	 *
	 * @throws Tp\InvalidSignatureException
	 * @throws Tp\SoapException
	 */
	public function setPaymentMethods(
		$type,
		?array $paymentMethods = null
	) : Tp\DataApi\Responses\SetPaymentMethodsResponse {
		return Tp\Helper\DataApi::setPaymentMethods($this->config, $type, $paymentMethods);
	}
}
