# ThePay

## Content

- [Configuration](#configuration)
- [Factories available in DI container](#factories-available-in-di-container)
- [Services available in DI container](#services-available-in-di-container)

## Configuration

You have to register this extension at first.

```yaml
extensions:
    contributte.thepay: Contributte\ThePay\DI\ThePayExtension
```

List of all options:

```yaml
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

```yaml
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
