<?php
declare(strict_types=1);

namespace Tests;

use Contributte\ThePay\DI\ThePayExtension;
use Nette\DI\Container;
use Nette\DI\Compiler;
use Tester\TestCase;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

class DITest extends TestCase
{
    public function testDemoConfig()
    {
        $thePayExtension = new ThePayExtension;

        $thePayExtension->setCompiler(new Compiler, 'container_' . __FUNCTION__);
        $thePayExtension->loadConfiguration();

        $thePayConfig = $thePayExtension->getConfig();

        Assert::same(
            [
                'demo'     => true,
                'merchant' => [
                    'gateUrl'             => 'https://www.thepay.cz/demo-gate/',
                    'merchantId'          => 1,
                    'accountId'           => 1,
                    'password'            => 'my$up3rsecr3tp4$$word',
                    'dataApiPassword'     => 'my$up3rsecr3tp4$$word',
                    'webServicesWsdl'     => 'https://www.thepay.cz/demo-gate/api/gate-api-demo.wsdl',
                    'dataWebServicesWsdl' => 'https://www.thepay.cz/demo-gate/api/data-demo.wsdl',
                ],
            ], $thePayConfig
        );
    }

    public function testProductionConfig()
    {
        $thePayExtension = new ThePayExtension;

        $thePayExtension->setCompiler(new Compiler, 'container_' . __FUNCTION__);

        $thePayExtension->setConfig(
            [
                'demo'     => false,
                'merchant' => [
                    'merchantId'      => 10,
                    'accountId'       => 42,
                    'password'        => 'abc',
                    'dataApiPassword' => 'def',
                ],
            ]
        );
        $thePayExtension->loadConfiguration();

        $thePayConfig = $thePayExtension->getConfig();

        Assert::same(
            [
                'demo'     => false,
                'merchant' => [
                    'gateUrl'             => 'https://www.thepay.cz/gate/',
                    'merchantId'          => 10,
                    'accountId'           => 42,
                    'password'            => 'abc',
                    'dataApiPassword'     => 'def',
                    'webServicesWsdl'     => 'https://www.thepay.cz/gate/api/gate-api.wsdl',
                    'dataWebServicesWsdl' => 'https://www.thepay.cz/gate/api/data.wsdl',
                ],
            ], $thePayConfig
        );
    }
}

$test = new DITest;
$test->run();
