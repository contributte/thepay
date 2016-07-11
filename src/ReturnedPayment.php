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

	/**
	 * @param string|null $backToEshopUrl
	 * @param array       $params
	 *
	 * @throws Nette\Application\UI\InvalidLinkException
	 */
	public function setBackToEshopUrl($backToEshopUrl = NULL, $params = [])
	{
		if ($backToEshopUrl !== NULL && preg_match('~^([\w:]+):(\w*+)(#.*)?()\z~', $backToEshopUrl)) {
			$backToEshopUrl = $this->linkGenerator->link($backToEshopUrl, $params);
		}

		parent::setBackToEshopUrl($backToEshopUrl);
	}

	public function __debugInfo(){
		$out=[];

		foreach ($this->__sleep() as $v) {
			$out[$v] = $this->$v;
		}

		return $out;
	}

	public function __sleep()
	{
		return array_diff(array_keys(get_object_vars($this)), [
			'request',
			'linkGenerator',
		]);
	}
}
