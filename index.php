<?php

require 'lib/autoload.php';

use test;
use bootstrap;


// Test
$test = new test();
$test->init();

// Bootstrap
$BS = new bootstrap();
$BS->init();