![](https://heatbadger.now.sh/github/readme/contributte/thepay/)

<p align=center>
	<a href="https://github.com/contributte/thepay/actions"><img src="https://badgen.net/github/checks/contributte/thepay"></a>
	<a href="https://coveralls.io/r/contributte/thepay"><img src="https://badgen.net/coveralls/c/github/contributte/thepay"></a>
	<a href="https://packagist.org/packages/contributte/thepay"><img src="https://badgen.net/packagist/dm/contributte/thepay"></a>
	<a href="https://packagist.org/packages/contributte/thepay"><img src="https://badgen.net/packagist/v/contributte/thepay"></a>
</p>
<p align=center>
	<a href="https://packagist.org/packages/contributte/thepay"><img src="https://badgen.net/packagist/php/contributte/thepay"></a>
	<a href="https://github.com/contributte/thepay"><img src="https://badgen.net/github/license/contributte/thepay"></a>
	<a href="https://bit.ly/ctteg"><img src="https://badgen.net/badge/support/gitter/cyan"></a>
	<a href="https://bit.ly/cttfo"><img src="https://badgen.net/badge/support/forum/yellow"></a>
	<a href="https://contributte.org/partners.html"><img src="https://badgen.net/badge/sponsor/donations/F96854"></a>
</p>

<p align=center>
Website 🚀 <a href="https://contributte.org">contributte.org</a> | Contact 👨🏻‍💻 <a href="https://f3l1x.io">f3l1x.io</a> | Twitter 🐦 <a href="https://twitter.com/contributte">@contributte</a>
</p>

Nette integration for [ThePay](https://www.thepay.cz/) API client with DI configuration and ready-to-use payment services.

## Versions

| State  | Version | Branch   | PHP      |
|--------|---------|----------|----------|
| dev    | `^5.0`  | `master` | `>= 8.2` |
| stable | `^4.1`  | `v4.x`   | `>= 7.1` |

## Contents

- [Installation](#installation)
- [Requirements](#requirements)
- [Configuration](#configuration)
- [Services Available in DI Container](#services-available-in-di-container)
- [Usage](#usage)
- [Official Documentation](#official-documentation)
- [Development](#development)

## Installation

To install the latest version of `contributte/thepay` use [Composer](https://getcomposer.org).

```bash
composer require contributte/thepay
```

## Requirements

This extension requires PSR-18 HTTP client and PSR-17 HTTP factories to be registered in your DI container.

The recommended implementation is [Guzzle](https://github.com/guzzle/guzzle):

```bash
composer require guzzlehttp/guzzle
```

You need to register the PSR interfaces in your neon config:

```neon
services:
	- GuzzleHttp\Client
	- GuzzleHttp\Psr7\HttpFactory
```

## Configuration

Register the extension:

```neon
extensions:
	contributte.thepay: Contributte\ThePay\DI\ThePayExtension
```

List of all options:

```neon
contributte.thepay:
	demo: true/false
	merchantId: 'your-merchant-id'
	projectId: (int)
	apiPassword: ''
	apiUrl: 'https://api.thepay.cz/'
	gateUrl: 'https://gate.thepay.cz/'
	language: 'cs'
```

Minimal production configuration:

```neon
contributte.thepay:
	merchantId: 'your-merchant-id'
	projectId: (int)
	apiPassword: ''
```

Demo configuration:

```neon
contributte.thepay:
	demo: true
	merchantId: 'your-merchant-id'
	projectId: (int)
	apiPassword: ''
```

When `demo: true` is set, `apiUrl` and `gateUrl` are automatically switched to demo endpoints.

## Services Available in DI Container

- `ThePay\ApiClient\TheConfig`
- `ThePay\ApiClient\Service\SignatureService`
- `ThePay\ApiClient\Service\ApiService` (implements `ApiServiceInterface`)
- `ThePay\ApiClient\Service\GateService` (implements `GateServiceInterface`)
- `ThePay\ApiClient\TheClient`

## Usage

### Create a Payment

```php
use Nette\Application\UI\Presenter;
use ThePay\ApiClient\Model\CreatePaymentParams;
use ThePay\ApiClient\TheClient;

class OrderPresenter extends Presenter
{
	public function __construct(
		private TheClient $thePayClient,
	) {
		parent::__construct();
	}

	public function actionPay(): void
	{
		$params = new CreatePaymentParams(10000, 'CZK', 'order-123');
		$payment = $this->thePayClient->createPayment($params);

		$this->redirectUrl($payment->getPayUrl());
	}
}
```

### Render Payment Buttons

```php
use Nette\Application\UI\Presenter;
use ThePay\ApiClient\Model\CreatePaymentParams;
use ThePay\ApiClient\TheClient;

class OrderPresenter extends Presenter
{
	public function __construct(
		private TheClient $thePayClient,
	) {
		parent::__construct();
	}

	public function renderPaymentMethods(): void
	{
		$params = new CreatePaymentParams(10000, 'CZK', 'order-123');
		$this->template->paymentButtons = $this->thePayClient->getPaymentButtons($params);
	}
}
```

### Verify a Payment

```php
use Nette\Application\UI\Presenter;
use ThePay\ApiClient\TheClient;

class OrderPresenter extends Presenter
{
	public function __construct(
		private TheClient $thePayClient,
	) {
		parent::__construct();
	}

	public function actionConfirmation(string $paymentUid): void
	{
		$payment = $this->thePayClient->getPayment($paymentUid);

		if ($payment->wasPaid()) {
			// Payment was successful.
		}
	}
}
```

## Official Documentation

- [ThePay API Client](https://github.com/ThePay/api-client)
- [ThePay API Documentation](https://thepay.docs.apiary.io/)

## Development

See [how to contribute](https://contributte.org/contributing.html) to this package.

This package is currently maintained by these authors.

<a href="https://github.com/trejjam">
	<img width="80" height="80" src="https://avatars2.githubusercontent.com/u/3594540?s=80&v=4">
</a>
<a href="https://github.com/f3l1x">
	<img width="80" height="80" src="https://avatars2.githubusercontent.com/u/538058?s=80&v=4">
</a>

-----

Consider to [support](https://contributte.org/partners.html) **contributte** development team.
Also thank you for using this package.
