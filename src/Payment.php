<?php
declare(strict_types=1);

namespace Trejjam\ThePay;

use Nette;
use Tp;

class Payment extends Tp\Payment
{
	/**
	 * @var Nette\Application\LinkGenerator
	 */
	protected $linkGenerator;

	public function __construct(
		MerchantConfig $config,
		Nette\Application\LinkGenerator $linkGenerator
	) {
		parent::__construct($config);

		$this->linkGenerator = $linkGenerator;
	}

	public function setReturnUrl(
		string $returnUrl,
		array $params = []
	) : void {
		if (preg_match('~^([\w:]+):(\w*+)(#.*)?()\z~', $returnUrl)) {
			$returnUrl = $this->linkGenerator->link($returnUrl, $params);
		}

		parent::setReturnUrl($returnUrl);
	}

	/**
	 * @param string|null $backToEshopUrl
	 *
	 * @throws Nette\Application\UI\InvalidLinkException
	 */
	public function setBackToEshopUrl(
		?string $backToEshopUrl = null,
		array $params = []
	) : void {
		if (
			$backToEshopUrl !== null
			&& preg_match('~^([\w:]+):(\w*+)(#.*)?()\z~', $backToEshopUrl)
		) {
			$backToEshopUrl = $this->linkGenerator->link($backToEshopUrl, $params);
		}

		parent::setBackToEshopUrl($backToEshopUrl);
	}

	public function getRedirectUrl() : string
	{
		$queryArgs = $this->getArgs();
		$queryArgs['signature'] = $this->getSignature();

		return $this->getMerchantConfig()->gateUrl . '?' . http_build_query($queryArgs);
	}

	public function redirectOnlinePayment(Nette\Application\UI\Presenter $presenter) : void
	{
		$presenter->redirectUrl($this->getRedirectUrl());
	}

	public function __debugInfo() : array
	{
		$out = [];

		foreach ($this->__sleep() as $property) {
			$out[$property] = $this->{$property};
		}

		return $out;
	}

	public function __sleep() : array
	{
		return array_diff(array_keys(get_object_vars($this)), [
			'linkGenerator',
		]);
	}
}
