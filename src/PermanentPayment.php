<?php
/**
 * Created by PhpStorm.
 * User: jam
 * Date: 20.8.15
 * Time: 11:16
 */

namespace Trejjam\ThePay;


use Nette,
	Tp,
	Trejjam;

class PermanentPayment extends Tp\PermanentPayment
{
	/**
	 * @var Nette\Application\LinkGenerator
	 */
	protected $linkGenerator;

	public function __construct(Tp\MerchantConfig $config, Nette\Application\LinkGenerator $linkGenerator)
	{
		parent::__construct($config, NULL, NULL, NULL);

		$this->linkGenerator = $linkGenerator;
	}

	public function getMerchantData()
	{
		if (is_null($this->merchantData)) {
			throw new PermanentPaymentException('Property merchantData was not set', PermanentPaymentException::UNDEFINED_PROPERTY);
		}

		return parent::getMerchantData();
	}

	public function getDescription()
	{
		if (is_null($this->description)) {
			throw new PermanentPaymentException('Property description was not set', PermanentPaymentException::UNDEFINED_PROPERTY);
		}

		return parent::getDescription();
	}

	public function getReturnUrl()
	{
		if (is_null($this->returnUrl)) {
			throw new PermanentPaymentException('Property returnUrl was not set', PermanentPaymentException::UNDEFINED_PROPERTY);
		}

		return parent::getReturnUrl();
	}

	public function setReturnUrl($returnUrl, $params = [])
	{
		if (preg_match('~^([\w:]+):(\w*+)(#.*)?()\z~', $returnUrl)) {
			$returnUrl = $this->linkGenerator->link($returnUrl, $params);
		}

		parent::setReturnUrl($returnUrl);
	}

	public function getSignature()
	{
		$this->getMerchantData();
		$this->getDescription();
		$this->getReturnUrl();

		return parent::getSignature();
	}

	public function getSignatureLite()
	{
		$this->getMerchantData();

		return parent::getSignatureLite();
	}
}
