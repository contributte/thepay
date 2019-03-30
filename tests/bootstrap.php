<?php declare(strict_types = 1);

$rootDit = dirname(__DIR__);
$logDir = $rootDit . '/log';
$tempDir = $rootDit . '/temp';

require $rootDit . '/vendor/autoload.php';

if ( !class_exists('Tester\Assert')) {
	echo "Install Nette Tester using `composer update --dev`\n";
	exit(1);
}

Tester\Environment::setup();

$configurator = new Nette\Configurator();
$configurator->setDebugMode(false);

Tracy\Debugger::$logDirectory = $logDir;
//$configurator->enableDebugger($logDir);
$configurator->setTempDirectory($tempDir);

@mkdir($logDir, 0775);
@mkdir($tempDir, 0775);

$configurator->addConfig(__DIR__ . '/config/config.neon');
$configurator->addConfig(__DIR__ . '/config/config.local.neon');

return $configurator->createContainer();
