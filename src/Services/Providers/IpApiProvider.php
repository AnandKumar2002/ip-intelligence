<?php

namespace Parvion\IpIntelligence\Services\Providers;

use Illuminate\Support\Facades\Http;
use Parvion\IpIntelligence\Contracts\GeoLocationProvider;

class IpApiProvider implements GeoLocationProvider
{
    /**
     * Get geolocation data for a given IP address using ip-api.com.
     *
     * @param string $ip
     * @return array
     */
    public function lookup(string $ip): array
    {
        try {
            $response = Http::timeout(3)->get("http://ip-api.com/json/{$ip}?fields=status,message,country,countryCode,region,regionName,city,zip,lat,lon,timezone,isp,org,as,query,currency");
            
            if ($response->successful() && $response->json('status') === 'success') {
                $data = $response->json();
                
                return [
                    'country'      => $data['country'] ?? null,
                    'country_code' => $data['countryCode'] ?? null,
                    'state'        => $data['regionName'] ?? null,
                    'city'         => $data['city'] ?? null,
                    'zip_code'     => $data['zip'] ?? null,
                    'latitude'     => $data['lat'] ?? null,
                    'longitude'    => $data['lon'] ?? null,
                    'timezone'     => $data['timezone'] ?? null,
                    'currency'     => $data['currency'] ?? null,
                    'isp'          => $data['isp'] ?? null,
                    'organization' => $data['org'] ?? null,
                    'asn'          => $data['as'] ?? null,
                ];
            }
        } catch (\Exception $e) {
            // Silently fail and return empty array on connection error
        }

        return [];
    }
}
