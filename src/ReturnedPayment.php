<?php declare(strict_types = 1);

namespace Contributte\ThePay;

use Nette\Application\LinkGenerator;
use Nette\Application\UI\InvalidLinkException;
use Nette\Http\IRequest;
use Tp;

class ReturnedPayment extends Tp\ReturnedPayment
{

	/** @var IRequest */
	protected $request;

	/** @var LinkGenerator */
	protected $linkGenerator;

	public function __construct(
		MerchantConfig $config,
		IRequest $request,
		LinkGenerator $linkGenerator
	)
	{
		$this->request = $request;

		parent::__construct($config, $this->request->getQuery());

		$this->linkGenerator = $linkGenerator;
	}

	/**
	 * @throws InvalidLinkException
	 */
	public function setReturnUrl(
		string $returnUrl,
		array $params = []
	): void
	{
		if (preg_match('~^([\w:]+):(\w*+)(#.*)?()\z~', $returnUrl) === 1) {
			$returnUrl = $this->linkGenerator->link($returnUrl, $params);
		}

		parent::setReturnUrl($returnUrl);
	}

	/**
	 * @throws InvalidLinkException
	 */
	public function setBackToEshopUrl(
		?string $backToEshopUrl = null,
		array $params = []
	): void
	{
		if (
			$backToEshopUrl !== null
			&& preg_match('~^([\w:]+):(\w*+)(#.*)?()\z~', $backToEshopUrl) === 1
		) {
			$backToEshopUrl = $this->linkGenerator->link($backToEshopUrl, $params);
		}

		parent::setBackToEshopUrl($backToEshopUrl);
	}

	/**
	 * @return mixed[]
	 */
	public function __debugInfo(): array
	{
		$out = [];

		foreach ($this->__sleep() as $property) {
			$out[$property] = $this->{$property};
		}

		return $out;
	}

	/**
	 * @return mixed[]
	 */
	public function __sleep(): array
	{
		return array_diff(array_keys(get_object_vars($this)), [
			'request',
			'linkGenerator',
		]);
	}

}
