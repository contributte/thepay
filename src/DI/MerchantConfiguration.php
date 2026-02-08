<?php declare(strict_types = 1);

namespace Contributte\ThePay\DI;

final class MerchantConfiguration
{

	public string $gateUrl = 'https://www.thepay.cz/gate/';

	public int $merchantId;

	public int $accountId;

	public string $password;

	public string $dataApiPassword;

	public string $webServicesWsdl = 'https://www.thepay.cz/gate/api/gate-api.wsdl';

	public string $dataWebServicesWsdl = 'https://www.thepay.cz/gate/api/data.wsdl';

}
