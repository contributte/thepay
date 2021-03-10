# ThePay

## Content

- [Configuration](#configuration)
- [Factories available in DI container](#factories-available-in-di-container)
- [Services available in DI container](#services-available-in-di-container)
- [Usage](#usage)

## Configuration

You have to register this extension at first.

```neon
extensions:
	contributte.thepay: Contributte\ThePay\DI\ThePayExtension
```

List of all options:

```neon
contributte.thepay:
	demo: true/false # if is true, extension rewrite merchant values with debug ones
	merchant:
		gateUrl: 'https://www.thepay.cz/gate/'
		merchantId: (int)
		accountId: (int)
		password: ''
		dataApiPassword: ''
		webServicesWsdl: 'https://www.thepay.cz/gate/api/api-demo.wsdl'
		dataWebServicesWsdl: 'https://www.thepay.cz/gate/api/data-demo.wsdl'
```

Minimal production configuration:

```neon
contributte.thepay:
	demo: false
	merchant:
		merchantId: (int)
		accountId: (int)
		password: ''
		dataApiPassword: ''
```

## Factories available in DI container

- `Contributte\ThePay\IPayment`
- `Contributte\ThePay\IPermanentPayment`
- `Contributte\ThePay\IReturnedPayment`
- `Contributte\ThePay\Helper\IRadioMerchant`

## Services available in DI container

- `Contributte\ThePay\MerchantConfig`
- `Contributte\ThePay\Helper\DataApi`

## Usage

Simple DTO for simple and type secure passing payment method information in the template

`PaymentMethodDTO.php`:
```php
final class PaymentMethodDTO {
	private string $paymentMethodName;
	private string $paymentIcon;
	private bool $isPaymentByCard;

	public function __construct(
		string $paymentMethodName,
		string $paymentIcon,
		bool $isPaymentByCard
	) {
		$this->paymentMethodName = $paymentMethodName;
		$this->paymentIcon = $paymentIcon;
		$this->isPaymentByCard = $isPaymentByCard;
	}

	public function getPaymentMethodName(): string {
		return $this->paymentMethodName;
	}

	public function getPaymentIcon(): string {
		return $this->paymentIcon;
	}

	public function isPaymentByCard(): bool {
		return $this->isPaymentByCard;
	}
}
```

Prepare list of available payment methods

`OrderPresenter.php`:

```php
use Contributte\ThePay\Helper\DataApi;
use Contributte\ThePay\Helper\IPaymentMethod;
use Nette\Application\UI\Presenter;

class OrderPresenter extend Presenter {
	/**
	 * @inject
	 */
	public DataApi $thePayDataApi;

	public function renderListMethods(): void {
		$template = $this->getTemplate();
		$paymentMethods = [];

		foreach ($this->thePayDataApi->getPaymentMethods()->getMethods() as $_paymentMethod) {
			$paymentIcon = $this->thePayDataApi->getPaymentMethodIcon($_paymentMethod, '209x127');
			$isPaymentByCard = $_paymentMethod->getId() === IPaymentMethod::CREDIT_CARD_PAYMENT_ID;
			$paymentName = $_paymentMethod->getId();

			$paymentMethods[$_paymentMethod->getId()] = new PaymentMethodDTO(
				$_paymentMethod->getName(),
				$paymentIcon,
				$isPaymentByCard
			);
		}

		$template->paymentMethods = $paymentMethods;
	}
}
```

`Order/listMethods.latte`:

```latte
<ul>
    <li n:foreach="$paymentMethods as $paymentMethodId => $paymentMethod">
        <a n:href="pay paymentMethodId => $paymentMethodId">
            <img n:if="$paymentMethod->getPaymentIcon() !== null" src="{$paymentMethod->getPaymentIcon()}" alt="{$paymentMethod->getPaymentMethodName()}" title="{$paymentMethod->getPaymentMethodName()}">
            <span n:if="$paymentMethod->getPaymentIcon() === null">{$paymentMethod->getPaymentMethodName()}</span>
        </a>
    </li>
</ul>
```

Payment is configured and invoked using following code

```php
use Contributte\ThePay\IPayment;
use Nette\Application\UI\Presenter;
use Tp\Payment;

class OrderPresenter extend Presenter {
	//...

	/**
	 * @inject
	 */
	public IPayment $tpPayment;

	public function actionPay(int $paymentMethodId): void {
		$payment = $this->tpPayment->create();
		assert($payment instanceof Payment);

		$payment->setMethodId($paymentMethodId);
		$payment->setValue(1000.0);
		$payment->setCurrency('CZK');
		$payment->setMerchantData('local-payment-unique-id');
		$payment->setMerchantSpecificSymbol('local-user-id');
		$payment->setReturnUrl($this->link('//onlineConfirmation', ['cartId' => 'local-payment-unique-id']));
		$payment->setBackToEshopUrl(
			'offlineConfirmation',
			['cartId' => 'local-payment-unique-id']
		);

		$payment->redirectOnlinePayment($this);
	}

	...
}
```

Now is user redirected to ThePay site, when payment is completed we receive:
- `HEAD` request to action `offlineConfirmation` with specified `cartId`
- `GET` request to action `onlineConfirmation` with specified `cartId`

In each offline or online action is necessary to active validate received data e.g. like this

```php
use Contributte\ThePay\Helper\DataApi;
use Contributte\ThePay\IReturnedPayment;
use Tp\InvalidSignatureException;
use Tp\ReturnedPayment;

class OrderPresenter extend Presenter {
	/**
	 * @inject
	 */
	public IReturnedPayment $tpReturnedPayment;

	/**
	 * @inject
	 */
	public DataApi $thePayDataApi;

	//...

	public function actionOnlineConfirmation(int $cartId): void {
		$returnedPayment = $this->tpReturnedPayment->create();

		try {
			if ($returnedPayment->verifySignature()) {
				if (in_array($returnedPayment->getStatus(), [
					ReturnedPayment::STATUS_OK,
					ReturnedPayment::STATUS_WAITING,
				], TRUE)) {
					//Demo gate doesn't allow active check...
					if ($this->thePayDataApi->getMerchantConfig()->isDemo()) {
						//Do not load thePayDataApi->getPayment

						if (bccomp(number_format($returnedPayment->getValue(), 2, '.', ''), '1000.00', 2) === 0) {
							// everything is ok
						}
					}
					else {
						$paymentId = $returnedPayment->getPaymentId();
						$payment = $this->thePayDataApi->getPayment($paymentId);

						if (bccomp(number_format($payment->getPayment()->getValue(), 2, '.', ''), '1000.00', 2) === 0) {
							// everything is ok
						}
					}
				}
			}
		}
		catch (InvalidSignatureException $e) {
			// TODO handle invalid request signature
		}
	}
}
```
