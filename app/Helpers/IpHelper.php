<?php

namespace App\Helpers;

class IpHelper
{
    /**
     * Subtract 1 from an IP address
     * 
     * @param string $ip The IP address to subtract from
     * @return string The resulting IP address
     */
    public static function subtractOne($ip)
    {
        if (empty($ip) || !filter_var($ip, FILTER_VALIDATE_IP)) {
            return $ip;
        }

        // Convert IP to long integer
        $long = ip2long($ip);
        
        if ($long === false) {
            return $ip;
        }

        // Subtract 1
        $long -= 1;

        // Convert back to IP address
        return long2ip($long);
    }
}
