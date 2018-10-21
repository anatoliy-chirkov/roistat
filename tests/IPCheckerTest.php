<?php

namespace tests;

require_once dirname(dirname(__FILE__)) . '/app/IPChecker.php';

use PHPUnit\Framework\TestCase;
use app\IPChecker;

class IPCheckerTest extends TestCase
{
    /**
     * @param $ip
     * @param $range
     * @param bool $expected
     * @dataProvider additionProvider
     * @throws \Exception
     */
    public function test($ip, $range, bool $expected)
    {
        $IPChecker = new IPChecker;
        $actual = $IPChecker->isIPInRange($ip, $range);

        $this->assertSame($expected, $actual);
    }

    public function additionProvider()
    {
        return [
            ['192.168.10.0', '192.168.10.0/2', true],
            ['73.35.143.63', '73.35.143.32/27', true],
            ['173.195.0.0', '173.167.34.89/10', false],
            ['4294967295', '255.255.255.255/32', true],
            ['qwerty', '255.255.255.255/32', false]
        ];
    }
}
