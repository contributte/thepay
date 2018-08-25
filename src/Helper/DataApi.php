<?php
declare(strict_types=1);

namespace Contributte\ThePay\Helper;

use Contributte\ThePay\MerchantConfig;
use Tp\DataApi\Parameters;
use Tp\DataApi\Responses\GetPaymentInstructionsResponse;
use Tp\DataApi\Responses\GetPaymentMethodsResponse;
use Tp\DataApi\Responses\GetPaymentResponse;
use Tp\DataApi\Responses\GetPaymentsResponse;
use Tp\DataApi\Responses\GetPaymentStateResponse;
use Tp\DataApi\Responses\SetPaymentMethodsResponse;
use Tp\Helper\DataApi as TpDataApi;
use Tp\InvalidSignatureException;
use Tp\SoapException;

class DataApi
{
	/**
	 * @var MerchantConfig
	 */
	protected $config;

	public function __construct(MerchantConfig $config)
	{
		$this->config = $config;
	}

	public function getMerchantConfig() : MerchantConfig
	{
		return $this->config;
	}

	public function getPaymentMethods(bool $onlyActive = true) : GetPaymentMethodsResponse
	{
		return TpDataApi::getPaymentMethods($this->config, $onlyActive);
	}

	/**
	 * @param string $type (tight|209x127|86x86)
	 */
	public function getPaymentMethodIcon(Parameters\MerchantAccountMethod $method, string $type = 'tight') : string
	{
		return "{$this->config->resourceUrl}/images/logos/public/{$type}/{$method->getId()}.png";
	}

	public function getPayment(int $paymentId) : GetPaymentResponse
	{
		return TpDataApi::getPayment($this->config, $paymentId);
	}

	public function getPaymentInstructions(int $paymentId) : GetPaymentInstructionsResponse
	{
		return TpDataApi::getPaymentInstructions($this->config, $paymentId);
	}

	public function getPaymentState(int $paymentId) : GetPaymentStateResponse
	{
		return TpDataApi::getPaymentState($this->config, $paymentId);
	}

	public function getPayments(
		?Parameters\GetPaymentsSearchParams $searchParams = null,
		?Parameters\PaginationRequest $pagination = null,
		?Parameters\Ordering $ordering = null
	) : GetPaymentsResponse {
		return TpDataApi::getPayments($this->config, $searchParams, $pagination, $ordering);
	}

	/**
	 * @param mixed $type
	 *
	 * @throws InvalidSignatureException
	 * @throws SoapException
	 */
	public function setPaymentMethods(
		$type,
		?array $paymentMethods = null
	) : SetPaymentMethodsResponse {
		return TpDataApi::setPaymentMethods($this->config, $type, $paymentMethods);
	}
}
