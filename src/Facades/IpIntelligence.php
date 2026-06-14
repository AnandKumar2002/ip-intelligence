<?php

namespace Parvion\IpIntelligence\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static string|null ip()
 * @method static \Parvion\IpIntelligence\IpIntelligence lookup(string $ip)
 * @method static string|null country()
 * @method static string|null state()
 * @method static string|null city()
 * @method static string|null zipCode()
 * @method static string|null timezone()
 * @method static float|null latitude()
 * @method static float|null longitude()
 * @method static array location()
 * @method static string|null deviceType()
 * @method static string|null device()
 * @method static string|null deviceBrand()
 * @method static string|null deviceModel()
 * @method static string|null browser()
 * @method static string|null browserVersion()
 * @method static string|null browserEngine()
 * @method static string|null platform()
 * @method static string|null platformVersion()
 * @method static string|null platformArchitecture()
 * @method static string|null userAgent()
 * @method static array parsedUserAgent()
 * @method static bool isBot()
 * @method static string|null botName()
 * @method static bool isVpn()
 * @method static bool isProxy()
 * @method static bool isTor()
 * @method static bool isSuspicious()
 * @method static array network()
 * @method static string|null language()
 * @method static string|null referrer()
 * @method static array all()
 *
 * @see \Parvion\IpIntelligence\IpIntelligence
 */
class IpIntelligence extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'ip-intelligence';
    }
}
