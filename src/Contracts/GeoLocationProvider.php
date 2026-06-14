<?php

namespace Parvion\IpIntelligence\Contracts;

interface GeoLocationProvider
{
    /**
     * Get geolocation data for a given IP address.
     *
     * @param string $ip
     * @return array
     */
    public function lookup(string $ip): array;
}
