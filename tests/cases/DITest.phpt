<?php
declare(strict_types=1);

namespace Tests;

use Contributte\ThePay\DI\ThePayExtension;
use Nette\DI\Container;
use Nette\DI\Compiler;
use Tester\TestCase;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

final class DITest extends TestCase
{
    private const NAME = 'contributte.thepay';

    public function testDemoConfig()
    {
        $thePayExtension = new ThePayExtension;

        $compiler = new Compiler;
        $compiler->addExtension(self::NAME, $thePayExtension);
        $compiler->addConfig(
            [
                self::NAME => [
                    'demo' => true,
                ],
            ]
        );
        $compiler->processExtensions();

        $thePayConfig = $thePayExtension->getConfig();

        Assert::same(true, $thePayConfig->demo);
        Assert::same('https://www.thepay.cz/demo-gate/', $thePayConfig->merchant->gateUrl);
        Assert::same(1, $thePayConfig->merchant->merchantId);
        Assert::same(1, $thePayConfig->merchant->accountId);
        Assert::same('my$up3rsecr3tp4$$word', $thePayConfig->merchant->password);
        Assert::same('my$up3rsecr3tp4$$word', $thePayConfig->merchant->dataApiPassword);
        Assert::same('https://www.thepay.cz/demo-gate/api/gate-api-demo.wsdl', $thePayConfig->merchant->webServicesWsdl);
        Assert::same('https://www.thepay.cz/demo-gate/api/data-demo.wsdl', $thePayConfig->merchant->dataWebServicesWsdl);
    }

    public function testProductionConfig()
    {
        $thePayExtension = new ThePayExtension;

        $compiler = new Compiler;
        $compiler->addExtension(self::NAME, $thePayExtension);
        $compiler->addConfig(
            [
                self::NAME => [
                    'demo'     => false,
                    'merchant' => [
                        'merchantId'      => 10,
                        'accountId'       => 42,
                        'password'        => 'abc',
                        'dataApiPassword' => 'def',
                    ],
                ],
            ]
        );
        $compiler->processExtensions();

        $thePayConfig = $thePayExtension->getConfig();

        Assert::same(false, $thePayConfig->demo);
        Assert::same('https://www.thepay.cz/gate/', $thePayConfig->merchant->gateUrl);
        Assert::same(10, $thePayConfig->merchant->merchantId);
        Assert::same(42, $thePayConfig->merchant->accountId);
        Assert::same('abc', $thePayConfig->merchant->password);
        Assert::same('def', $thePayConfig->merchant->dataApiPassword);
        Assert::same('https://www.thepay.cz/gate/api/gate-api.wsdl', $thePayConfig->merchant->webServicesWsdl);
        Assert::same('https://www.thepay.cz/gate/api/data.wsdl', $thePayConfig->merchant->dataWebServicesWsdl);
    }
}

$test = new DITest;
$test->run();
