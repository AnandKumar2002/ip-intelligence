<?php

namespace Parvion\IpIntelligence\Services;

use Illuminate\Support\Facades\Cache;
use Parvion\IpIntelligence\Contracts\GeoLocationProvider;
use Parvion\IpIntelligence\Services\Providers\IpApiProvider;

class GeoLocationService
{
    protected GeoLocationProvider $provider;

    public function __construct()
    {
        $this->provider = new IpApiProvider();
    }

    /**
     * Get geolocation data for the given IP address, with caching.
     *
     * @param string $ip
     * @return array
     */
    public function lookup(string $ip): array
    {
        // Don't lookup local IPs
        if ($ip === '127.0.0.1' || $ip === '::1') {
            return [];
        }

        $cacheEnabled = config('ip-intelligence.cache.enabled', true);

        if (!$cacheEnabled) {
            return $this->provider->lookup($ip);
        }

        $ttl = config('ip-intelligence.cache.ttl', 2592000);
        $prefix = config('ip-intelligence.cache.prefix', 'ip_intelligence:');
        $cacheKey = $prefix . $ip;

        return Cache::remember($cacheKey, $ttl, function () use ($ip) {
            return $this->provider->lookup($ip);
        });
    }
}
