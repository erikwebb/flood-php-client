#!/usr/bin/env php
<?php

require __DIR__ . '/vendor/autoload.php';

use Symfony\Component\Console\Application;
use Flood\Command\Flood;
use Flood\Command\Grid;

$application = new Application();
$application->add(new Flood);
$application->add(new Grid);
$application->run();
