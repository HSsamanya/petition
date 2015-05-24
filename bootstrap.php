<?php

require_once  __DIR__ . DIRECTORY_SEPARATOR.'includes'.DIRECTORY_SEPARATOR.'Spl4Autoloader.php';

$loader = new \petition\includes\Spl4Autoloader();
$loader->register();

$loader->addNamespace('petition\includes', __DIR__.'/includes');
