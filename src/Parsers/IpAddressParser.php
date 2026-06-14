<?php

namespace Parvion\IpIntelligence\Parsers;

use Illuminate\Http\Request;

class IpAddressParser
{
    /**
     * Parse the most accurate client IP address from the request.
     * Handles proxies and Cloudflare.
     *
     * @param \Illuminate\Http\Request $request
     * @return string|null
     */
    public static function parse(Request $request): ?string
    {
        $headers = [
            'CF-Connecting-IP',
            'X-Forwarded-For',
            'X-Real-IP',
            'HTTP_CLIENT_IP',
            'HTTP_X_FORWARDED_FOR',
        ];

        foreach ($headers as $header) {
            $ip = $request->header($header) ?? $request->server($header);
            
            if ($ip) {
                // If multiple IPs (e.g. X-Forwarded-For: client, proxy1, proxy2)
                $ips = array_map('trim', explode(',', $ip));
                foreach ($ips as $extractedIp) {
                    if (filter_var($extractedIp, FILTER_VALIDATE_IP)) {
                        return $extractedIp;
                    }
                }
            }
        }

        return $request->ip();
    }
}
