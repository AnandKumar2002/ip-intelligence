<?php

namespace Parvion\IpIntelligence\Concerns;

use Parvion\IpIntelligence\Services\GeoLocationService;

trait HasGeoLocation
{
    /**
     * Geolocation service instance.
     *
     * @var GeoLocationService|null
     */
    protected $geoService = null;

    /**
     * Cached location data for the current request.
     *
     * @var array|null
     */
    protected $locationData = null;

    /**
     * Resolve the geolocation service.
     *
     * @return GeoLocationService
     */
    protected function geoService(): GeoLocationService
    {
        if (!$this->geoService) {
            $this->geoService = new GeoLocationService();
        }

        return $this->geoService;
    }

    /**
     * Get the geolocation data for the current IP.
     *
     * @return array
     */
    public function location(): array
    {
        if ($this->locationData === null) {
            $ip = $this->ip();
            $this->locationData = $ip ? $this->geoService()->lookup($ip) : [];
        }

        return $this->locationData;
    }

    /**
     * Get the Country Name.
     *
     * @return string|null
     */
    public function country(): ?string
    {
        return $this->location()['country'] ?? null;
    }

    /**
     * Get the State / Region.
     *
     * @return string|null
     */
    public function state(): ?string
    {
        return $this->location()['state'] ?? null;
    }

    /**
     * Get the City.
     *
     * @return string|null
     */
    public function city(): ?string
    {
        return $this->location()['city'] ?? null;
    }

    /**
     * Get the Zip Code.
     *
     * @return string|null
     */
    public function zipCode(): ?string
    {
        return $this->location()['zip_code'] ?? null;
    }

    /**
     * Get the Timezone.
     *
     * @return string|null
     */
    public function timezone(): ?string
    {
        return $this->location()['timezone'] ?? null;
    }

    /**
     * Get the Latitude.
     *
     * @return float|null
     */
    public function latitude(): ?float
    {
        return $this->location()['latitude'] ?? null;
    }

    /**
     * Get the Longitude.
     *
     * @return float|null
     */
    public function longitude(): ?float
    {
        return $this->location()['longitude'] ?? null;
    }

    /**
     * Get the Currency.
     *
     * @return string|null
     */
    public function currency(): ?string
    {
        return $this->location()['currency'] ?? null;
    }
}
