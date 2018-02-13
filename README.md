ThePay
======

[![Latest stable](https://img.shields.io/packagist/v/trejjam/thepay.svg)](https://packagist.org/packages/trejjam/thepay)

Installation
------------

The best way to install trejjam/thepay is using  [Composer](http://getcomposer.org/):

```sh
$ composer require trejjam/thepay
```

Configuration
-------------

```yml
extensions:
	trejjam.thepay: Trejjam\ThePay\DI\ThePayExtension

trejjam.thepay:
	demo: true #if is true, extension rewrite merchant values
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
```yml
trejjam.thepay:
	demo: false
	merchant:
		merchantId: (int)
		accountId: (int)
		password: ''
		dataApiPassword: ''
```
