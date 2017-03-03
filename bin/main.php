<?php

require __DIR__ . '/../vendor/autoload.php';

use Flamingo\Flamingo;
use Flamingo\ErrorHandler;
use Analog\Analog;

// Create base task runner
$flamingo = new Flamingo(
    file_get_contents(__DIR__ . '/default.yml')
);

// Get app configuration
$appConf = $GLOBALS['FLAMINGO']['CONF']['App'];

// No configuration file found
if (!file_exists(getcwd() . '/flamingo.yml')) {
    echo 'No flamingo.yml file found in ' . getcwd() . PHP_EOL;
    exit;
}

// Add project configuration
$flamingo->addConfiguration(
    file_get_contents(getcwd() . '/flamingo.yml')
);

// Get log configuration
$logConf = $GLOBALS['FLAMINGO']['CONF']['Log'];

// Register error handler
Analog::handler(ErrorHandler::init($logConf['Debug']));

// Output app usage
if (in_array('-h', $argv) || in_array('--help', $argv)) {
    echo $appConf['Usage'];
    exit;
}

// Output current version
if (in_array('-v', $argv) || in_array('--version', $argv)) {
    echo $appConf['Name'] . ' ' . $appConf['Version'] . PHP_EOL;
    exit;
}

// Run defined task
$flamingo->run(!empty($argv[1]) ? $argv[1] : 'default');