<?php

require_once 'app/IPChecker.php';

use app\IPChecker;

if (!in_array(PHP_SAPI, ['cli', 'cli-server'])) {
    echo "Error: This program only for console using \n";
    die;
}

if ($argc < 3) {
    echo "Error: You need to define 2 arguments: ip, range \n";
    die;
}

$ip = $argv[1];
$range = $argv[2];

$IPChecker = new IPChecker();

try {
    $isIPInRange = $IPChecker->isIPInRange($ip, $range);
    echo $isIPInRange ? "IP in range \n" : "IP not in range \n";
} catch (\Exception $e) {
    echo "Error. Please check input params: {$e->getMessage()} \n";
}

die;
