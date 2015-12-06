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

	public function __construct(MerchantConfig $config = NULL, Nette\Http\IRequest $request, Nette\Application\LinkGenerator $linkGenerator)
	{
		$this->request = $request;

		parent::__construct($config, $this->request->getQuery());

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
