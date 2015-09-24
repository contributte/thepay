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
	/**
	 * @var Tp\MerchantConfig
	 */
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
	 * @param Tp\DataApi\MerchantAccountMethod $method
	 * @return null|string
	 */
	public function getPaymentMethodIcon(Tp\DataApi\MerchantAccountMethod $method)
	{
		switch ($method->getName()) {
			case 'Platba kartou':
				$file = 'muzo';
				break;
			case 'Platba 24':
				$file = 'platba24';
				break;
			case 'MojePlatba':
				$file = 'moje-platba';
				break;
			case 'eKonto':
				$file = 'ekonto';
				break;
			case 'mPeníze':
				$file = 'mpenize';
				break;
			case 'Ge Money':
				$file = 'gemoney';
				break;
			case 'ČSOB':
				$file = 'csob';
				break;
			case 'Fio banka':
				$file = 'fio';
				break;
			case 'Jiná banka':
				$file = 'transfer';
				break;
			case 'SuperCash':
				$file = 'super-cash';
				break;
			case 'FerBuy':
				$file = 'ferbuy';
				break;

			default:

				return NULL;
		}

		return $this->config->gateUrl . 'radiobuttons/style/icons/' . $file . '.png';
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
