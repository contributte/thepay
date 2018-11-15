<?php declare(strict_types = 1);

require dirname(__DIR__) . '/vendor/autoload.php';

if ( !class_exists('Tester\Assert')) {
	echo "Install Nette Tester using `composer update --dev`\n";
	exit(1);
}

Tester\Environment::setup();

$configurator = new Nette\Configurator();
$configurator->setDebugMode(false);

Tracy\Debugger::$logDirectory = dirname(__DIR__) . '/log';
//$configurator->enableDebugger(dirname(__DIR__) . '/log');
$configurator->setTempDirectory(dirname(__DIR__) . '/temp');

@mkdir(Tracy\Debugger::$logDirectory, 0775);

$configurator->addConfig(__DIR__ . '/config/config.neon');
$configurator->addConfig(__DIR__ . '/config/config.local.neon');
return $configurator->createContainer();
