<?php
require_once './vendor/autoload.php';

use Classes\Elevator;

$app = new Elevator(defaultValue());

$app->run();