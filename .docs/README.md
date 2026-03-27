# ThePay

## Content

- [Requirements](#requirements)
- [Configuration](#configuration)
- [Services available in DI container](#services-available-in-di-container)
- [Usage](#usage)

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

## Services available in DI container

- `ThePay\ApiClient\TheConfig`
- `ThePay\ApiClient\Service\SignatureService`
- `ThePay\ApiClient\Service\ApiService` (implements `ApiServiceInterface`)
- `ThePay\ApiClient\Service\GateService` (implements `GateServiceInterface`)
- `ThePay\ApiClient\TheClient`

## Usage

### Create a payment

```php
use ThePay\ApiClient\TheClient;
use ThePay\ApiClient\Model\CreatePaymentParams;
use Nette\Application\UI\Presenter;

class OrderPresenter extends Presenter
{

	public function __construct(
		private TheClient $thePayClient,
	)
	{
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

### Render payment buttons

```php
use ThePay\ApiClient\TheClient;
use ThePay\ApiClient\Model\CreatePaymentParams;
use Nette\Application\UI\Presenter;

class OrderPresenter extends Presenter
{

	public function __construct(
		private TheClient $thePayClient,
	)
	{
		parent::__construct();
	}

	public function renderPaymentMethods(): void
	{
		$params = new CreatePaymentParams(10000, 'CZK', 'order-123');
		$this->template->paymentButtons = $this->thePayClient->getPaymentButtons($params);
	}

}
```

### Verify a payment

```php
use ThePay\ApiClient\TheClient;
use Nette\Application\UI\Presenter;

class OrderPresenter extends Presenter
{

	public function __construct(
		private TheClient $thePayClient,
	)
	{
		parent::__construct();
	}

	public function actionConfirmation(string $paymentUid): void
	{
		$payment = $this->thePayClient->getPayment($paymentUid);

		if ($payment->wasPaid()) {
			// payment was successful
		}
	}

}
```

## Official documentation

- [ThePay API Client](https://github.com/ThePay/api-client)
- [ThePay API Documentation](https://thepay.docs.apiary.io/)
