<?php declare(strict_types = 1);

namespace Contributte\ThePay;

use Nette\Application\LinkGenerator;
use Tp;

class PermanentPayment extends Tp\PermanentPayment
{

	/** @var LinkGenerator */
	protected $linkGenerator;

	public function __construct(
		MerchantConfig $config,
		LinkGenerator $linkGenerator
	)
	{
		parent::__construct($config, null, null, null);

		$this->linkGenerator = $linkGenerator;
	}

	public function getMerchantData(): string
	{
		if ($this->merchantData === null) {
			throw new PermanentPaymentException('Property merchantData was not set', PermanentPaymentException::UNDEFINED_PROPERTY);
		}

		return $this->merchantData;
	}

	public function getDescription(): string
	{
		if ($this->description === null) {
			throw new PermanentPaymentException('Property description was not set', PermanentPaymentException::UNDEFINED_PROPERTY);
		}

		return $this->description;
	}

	public function getReturnUrl(): string
	{
		if ($this->returnUrl === null) {
			throw new PermanentPaymentException('Property returnUrl was not set', PermanentPaymentException::UNDEFINED_PROPERTY);
		}

		return $this->returnUrl;
	}

	public function setReturnUrl(string $returnUrl, array $params = []): void
	{
		if (preg_match('~^([\w:]+):(\w*+)(#.*)?()\z~', $returnUrl)) {
			$returnUrl = $this->linkGenerator->link($returnUrl, $params);
		}

		parent::setReturnUrl($returnUrl);
	}

	public function getSignature(): string
	{
		return parent::getSignature();
	}

	public function getSignatureLite(): string
	{
		$this->getMerchantData();

		return parent::getSignatureLite();
	}

}
