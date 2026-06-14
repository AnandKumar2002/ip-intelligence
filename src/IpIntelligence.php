<?php

namespace Parvion\IpIntelligence;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Http\Request;
use JsonSerializable;
use Parvion\IpIntelligence\Concerns\HasIpDetection;
use Parvion\IpIntelligence\Concerns\HasDeviceDetection;
use Parvion\IpIntelligence\Concerns\HasGeoLocation;
use Parvion\IpIntelligence\Concerns\HasSecurityDetection;
use Parvion\IpIntelligence\Concerns\HasNetworkDetection;

class IpIntelligence implements Arrayable, Jsonable, JsonSerializable
{
    use HasIpDetection;
    use HasDeviceDetection;
    use HasGeoLocation;
    use HasSecurityDetection;
    use HasNetworkDetection;

    /**
     * The current request instance.
     *
     * @var \Illuminate\Http\Request
     */
    protected $request;

    /**
     * Create a new IpIntelligence instance.
     *
     * @param  \Illuminate\Http\Request|null  $request
     * @return void
     */
    public function __construct(?Request $request = null)
    {
        $this->request = $request ?: request();
    }

    /**
     * Get all IP intelligence data as an array.
     *
     * @return array
     */
    public function all(): array
    {
        return [
            'ip'       => $this->ip(),
            'location' => $this->location(),
            'user_agent' => $this->userAgent(),
            'browser'  => [
                'name'    => $this->browser(),
                'version' => $this->browserVersion(),
                'engine'  => $this->browserEngine(),
            ],
            'device'   => [
                'type'  => $this->deviceType(),
                'name'  => $this->device(),
                'brand' => $this->deviceBrand(),
                'model' => $this->deviceModel(),
            ],
            'platform' => [
                'name'         => $this->platform(),
                'version'      => $this->platformVersion(),
                'architecture' => $this->platformArchitecture(),
            ],
            'security' => [
                'is_bot'        => $this->isBot(),
                'bot_name'      => $this->botName(),
                'is_vpn'        => $this->isVpn(),
                'is_proxy'      => $this->isProxy(),
                'is_tor'        => $this->isTor(),
                'is_suspicious' => $this->isSuspicious(),
            ],
            'network'  => $this->network(),
            'language' => $this->language(),
            'referrer' => $this->referrer(),
        ];
    }

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return $this->all();
    }

    /**
     * Convert the object to its JSON representation.
     *
     * @param  int  $options
     * @return string
     */
    public function toJson($options = 0): string
    {
        return json_encode($this->toArray(), $options);
    }

    /**
     * Convert the object into something JSON serializable.
     *
     * @return array
     */
    public function jsonSerialize(): mixed
    {
        return $this->toArray();
    }

    /**
     * Get Browser / Preferred Language.
     *
     * @return string|null
     */
    public function language(): ?string
    {
        return $this->request->getLanguages()[0] ?? null;
    }

    /**
     * Get HTTP Referrer details.
     *
     * @return string|null
     */
    public function referrer(): ?string
    {
        return $this->request->headers->get('referer');
    }
}
