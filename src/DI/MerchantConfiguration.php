<?php declare(strict_types = 1);

namespace Contributte\ThePay\DI;

final class MerchantConfiguration
{

	/** @var string */
	public $gateUrl = 'https://www.thepay.cz/gate/';

	/** @var int */
	public $merchantId;

	/** @var int */
	public $accountId;

	/** @var string */
	public $password;

	/** @var string */
	public $dataApiPassword;

	/** @var string */
	public $webServicesWsdl = 'https://www.thepay.cz/gate/api/gate-api.wsdl';

	/** @var string */
	public $dataWebServicesWsdl = 'https://www.thepay.cz/gate/api/data.wsdl';

}
