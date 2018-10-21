<?php

namespace app;

class IPChecker
{
    /**
     * @param $ip
     * @param $range
     * @return bool
     * @throws \Exception
     */
    public function isIPInRange(string $ip, $range)
    {
        $this->validateRange($range);
        $ip = $this->getPreparedIP($ip);

        if (is_array($range)) {
            return $this->isIPInArrayRange($ip, $range);
        } else {
            return $this->isIPInRangeRow($ip, $range);
        }
    }

    /**
     * @param string $ip
     * @return string
     */
    private function getPreparedIP(string $ip)
    {
        if ($this->isInt($ip)) {
            return long2ip((int)$ip);
        } else {
            return $ip;
        }
    }

    /**
     * @param string $value
     * @return bool
     */
    private function isInt(string $value)
    {
        return strlen($value) === strlen((int)$value);
    }

    /**
     * @param string $ip
     * @param array $ranges
     * @return bool
     * @throws \Exception
     */
    private function isIPInArrayRange(string $ip, array $ranges)
    {
        foreach ($ranges as $range) {
            if (is_numeric($range) || is_float($range) || is_int($range)) {
                if ($this->isIPInRangeRow($ip, $range)) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * @param string $ip
     * @param $range
     * @return bool
     * @throws \Exception
     */
    private function isIPInRangeRow(string $ip, $range)
    {
        if ($this->isCIDR($range)) {
            return $this->isIPInCIDR($ip, $range);
        } else if ($this->isInt($range)) {
            return $ip == long2ip($range);
        }

        throw new \Exception('Unknown range type');
    }

    /**
     * @param $ip
     * @param string $range
     * @return bool
     */
    private function isIPInCIDR(string $ip, string $range)
    {
        if (!$this->isCIDR($range)) {
            $range .= '/32';
        }

        list($range, $netmask) = explode('/', $range, 2);
        $rangeDecimal = ip2long($range);
        $ipDecimal = ip2long($ip);
        $wildcardDecimal = pow(2, (32 - $netmask)) - 1;
        $netmaskDecimal =~ $wildcardDecimal;

        return (($ipDecimal & $netmaskDecimal) == ($rangeDecimal & $netmaskDecimal));
    }

    /**
     * @param $value
     * @throws \Exception
     */
    private function validateRange($value)
    {
        if (!is_string($value) && !is_array($value)) {
            throw new \Exception('Invalid range');
        }
    }

    /**
     * @param string $value
     * @return bool
     */
    private function isCIDR(string $value)
    {
        return strpos($value, '/') !== false;
    }
}
