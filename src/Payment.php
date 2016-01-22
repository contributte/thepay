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

class Payment extends Tp\Payment
{
	/**
	 * @var Nette\Application\LinkGenerator
	 */
	protected $linkGenerator;

	public function __construct(MerchantConfig $config = NULL, Nette\Application\LinkGenerator $linkGenerator)
	{
		parent::__construct($config);

		$this->linkGenerator = $linkGenerator;
	}

	public function setReturnUrl($returnUrl, $params = [])
	{
		if (preg_match('~^([\w:]+):(\w*+)(#.*)?()\z~', $returnUrl)) {
			$returnUrl = $this->linkGenerator->link($returnUrl, $params);
		}

		parent::setReturnUrl($returnUrl);
	}

	public function redirectOnlinePayment(Nette\Application\UI\Presenter $presenter)
	{
		$queryArgs = $this->getArgs();
		$queryArgs['signature'] = $this->getSignature();
		$url = $this->getMerchantConfig()->gateUrl . '?' . http_build_query($queryArgs);

		$presenter->redirectUrl($url);
	}

	public function __debugInfo()
	{
		$out = [];

		foreach ($this->__sleep() as $v) {
			$out[$v] = $this->$v;
		}

		return $out;
	}

	public function __sleep()
	{
		return array_diff(array_keys(get_object_vars($this)), [
			'linkGenerator',
		]);
	}
}
