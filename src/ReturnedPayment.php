<?php
declare(strict_types=1);

namespace Trejjam\ThePay;

use Nette;
use Tp;

class ReturnedPayment extends Tp\ReturnedPayment
{
	/**
	 * @var Nette\Http\IRequest
	 */
	protected $request;
	/**
	 * @var Nette\Application\LinkGenerator
	 */
	protected $linkGenerator;

	public function __construct(
		MerchantConfig $config = NULL,
		Nette\Http\IRequest $request,
		Nette\Application\LinkGenerator $linkGenerator
	) {
		$this->request = $request;

		parent::__construct($config, $this->request->getQuery());

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
	 * @param array       $params
	 *
	 * @throws Nette\Application\UI\InvalidLinkException
	 */
	public function setBackToEshopUrl(
		string $backToEshopUrl = NULL,
		array $params = []
	) : void {
		if (
			$backToEshopUrl !== NULL
			&& preg_match('~^([\w:]+):(\w*+)(#.*)?()\z~', $backToEshopUrl)
		) {
			$backToEshopUrl = $this->linkGenerator->link($backToEshopUrl, $params);
		}

		parent::setBackToEshopUrl($backToEshopUrl);
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
			'request',
			'linkGenerator',
		]);
	}
}
