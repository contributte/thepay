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

	public function __construct(Tp\MerchantConfig $config = NULL, Nette\Application\LinkGenerator $linkGenerator)
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
}
