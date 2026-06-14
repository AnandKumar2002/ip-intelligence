<?php

namespace Parvion\IpIntelligence\Parsers;

class UserAgentParser
{
    protected string $userAgent;

    public function __construct(string $userAgent)
    {
        $this->userAgent = $userAgent;
    }

    public function parse(): array
    {
        return [
            'platform'         => $this->getPlatform(),
            'platform_version' => $this->getPlatformVersion(),
            'browser'          => $this->getBrowser(),
            'browser_version'  => $this->getBrowserVersion(),
            'device_type'      => $this->getDeviceType(),
            'device'           => $this->getDeviceName(),
            'device_brand'     => $this->getDeviceBrand(),
            'device_model'     => $this->getDeviceModel(),
            'is_bot'           => $this->isBot(),
            'bot_name'         => $this->getBotName(),
        ];
    }

    public function getPlatform(): ?string
    {
        $platforms = [
            'Windows' => 'Windows',
            'iPad'    => 'iPad',
            'iPod'    => 'iPod',
            'iPhone'  => 'iPhone',
            'Mac'     => 'Macintosh|Mac OS X',
            'Android' => 'Android',
            'Linux'   => 'Linux',
            'Ubuntu'  => 'Ubuntu',
            'Chrome OS' => 'CrOS',
        ];

        foreach ($platforms as $platform => $regex) {
            if (preg_match("/{$regex}/i", $this->userAgent)) {
                return $platform;
            }
        }
        return null;
    }

    public function getPlatformVersion(): ?string
    {
        $platform = $this->getPlatform();
        
        if ($platform === 'Windows' && preg_match('/Windows NT (\d+\.\d+)/i', $this->userAgent, $matches)) {
            $versions = ['10.0' => '10/11', '6.3' => '8.1', '6.2' => '8', '6.1' => '7', '6.0' => 'Vista', '5.1' => 'XP'];
            return $versions[$matches[1]] ?? $matches[1];
        }

        if (in_array($platform, ['Mac', 'iOS', 'iPhone', 'iPad']) && preg_match('/OS (?:X )?(\d+[_.]\d+(?:[_.]\d+)?)/i', $this->userAgent, $matches)) {
            return str_replace('_', '.', $matches[1]);
        }

        if ($platform === 'Android' && preg_match('/Android (\d+(?:\.\d+)?)/i', $this->userAgent, $matches)) {
            return $matches[1];
        }

        return null;
    }

    public function getBrowser(): ?string
    {
        $browsers = [
            'Edge'      => 'Edg|Edge',
            'Opera'     => 'OPR|Opera',
            'Firefox'   => 'Firefox',
            'Chrome'    => 'Chrome',
            'Safari'    => 'Safari',
            'IE'        => 'MSIE|Trident',
            'Brave'     => 'Brave',
            'Vivaldi'   => 'Vivaldi',
            'UCBrowser' => 'UCBrowser',
        ];

        foreach ($browsers as $browser => $regex) {
            if (preg_match("/{$regex}/i", $this->userAgent)) {
                // Exclude Chrome if it's Edge, Opera, etc.
                if ($browser === 'Safari' && preg_match('/Chrome/i', $this->userAgent)) continue;
                if ($browser === 'Chrome' && preg_match('/Edg|OPR/i', $this->userAgent)) continue;
                return $browser;
            }
        }
        return null;
    }

    public function getBrowserVersion(): ?string
    {
        $browser = $this->getBrowser();
        $regexes = [
            'Edge'    => '/Edg(?:e)?\/(\d+(?:\.\d+)?)/i',
            'Opera'   => '/OPR\/(\d+(?:\.\d+)?)/i',
            'Firefox' => '/Firefox\/(\d+(?:\.\d+)?)/i',
            'Chrome'  => '/Chrome\/(\d+(?:\.\d+)?)/i',
            'Safari'  => '/Version\/(\d+(?:\.\d+)?)/i',
            'IE'      => '/(?:MSIE |rv:)(\d+(?:\.\d+)?)/i',
        ];

        if (isset($regexes[$browser]) && preg_match($regexes[$browser], $this->userAgent, $matches)) {
            return $matches[1];
        }
        return null;
    }

    public function getBrowserEngine(): ?string
    {
        if (preg_match('/AppleWebKit/i', $this->userAgent)) {
            if (preg_match('/Chrome|Edg|OPR/i', $this->userAgent)) return 'Blink';
            return 'WebKit';
        }
        if (preg_match('/Gecko(?!.*like)/i', $this->userAgent) || preg_match('/Firefox/i', $this->userAgent)) return 'Gecko';
        if (preg_match('/Trident|MSIE/i', $this->userAgent)) return 'Trident';
        if (preg_match('/Presto/i', $this->userAgent)) return 'Presto';
        
        return null;
    }

    public function getPlatformArchitecture(): ?string
    {
        if (preg_match('/x86_64|Win64|WOW64|amd64/i', $this->userAgent)) return 'x64';
        if (preg_match('/i386|i686|x86/i', $this->userAgent)) return 'x86';
        if (preg_match('/arm64|aarch64|AppleSilicon|MacAppleSilicon/i', $this->userAgent)) return 'ARM64';
        if (preg_match('/arm/i', $this->userAgent)) return 'ARM';

        return null;
    }

    public function getDeviceType(): string
    {
        if ($this->isBot()) return 'Bot';
        
        $mobileRegex = '/Mobile|Android|iP(hone|od)|IEMobile|BlackBerry|Kindle|Silk-Accelerated|(hpw|web)OS|Opera M(obi|ini)/i';
        $tabletRegex = '/Tablet|iPad|Playbook|Silk/i';
        $tvRegex = '/SmartTV|AppleTV|GoogleTV|HbbTV|NetCast|Roku|Web0S/i';

        if (preg_match($tabletRegex, $this->userAgent)) {
            // Exceptions where Android Tablets have "Mobile" in UA
            return 'Tablet';
        }
        if (preg_match($mobileRegex, $this->userAgent)) return 'Mobile';
        if (preg_match($tvRegex, $this->userAgent)) return 'TV';

        return 'Desktop';
    }

    public function getDeviceName(): ?string
    {
        if (preg_match('/iPhone/i', $this->userAgent)) return 'iPhone';
        if (preg_match('/iPad/i', $this->userAgent)) return 'iPad';
        if (preg_match('/Pixel/i', $this->userAgent)) return 'Pixel';
        if (preg_match('/Samsung/i', $this->userAgent)) return 'Samsung';
        if (preg_match('/OnePlus/i', $this->userAgent)) return 'OnePlus';
        if (preg_match('/Galaxy/i', $this->userAgent)) return 'Samsung Galaxy';
        
        return $this->getDeviceType(); // Fallback to type if specific name isn't found
    }

    public function getDeviceBrand(): ?string
    {
        if (preg_match('/iPhone|iPad|Macintosh|Mac OS/i', $this->userAgent)) return 'Apple';
        if (preg_match('/Samsung|SM-|GT-|SCH-|SGH-/i', $this->userAgent)) return 'Samsung';
        if (preg_match('/Pixel|Nexus/i', $this->userAgent)) return 'Google';
        if (preg_match('/Huawei|Honor|HUA|VOG-/i', $this->userAgent)) return 'Huawei';
        if (preg_match('/Xiaomi|Mi |Redmi|POCO/i', $this->userAgent)) return 'Xiaomi';
        if (preg_match('/OnePlus|ONEPLUS/i', $this->userAgent)) return 'OnePlus';
        if (preg_match('/Oppo|CPH/i', $this->userAgent)) return 'Oppo';
        if (preg_match('/Vivo|V\d{4}/i', $this->userAgent)) return 'Vivo';
        if (preg_match('/Sony|Xperia/i', $this->userAgent)) return 'Sony';
        if (preg_match('/Motorola|Moto/i', $this->userAgent)) return 'Motorola';
        if (preg_match('/Nokia|Lumia/i', $this->userAgent)) return 'Nokia';
        
        return null;
    }

    public function getDeviceModel(): ?string
    {
        // Samsung
        if (preg_match('/\b((?:SM|GT|SCH|SGH)-[A-Z0-9]+)\b/i', $this->userAgent, $matches)) return $matches[1];
        // Google Pixel
        if (preg_match('/\b(Pixel [0-9a-zA-Z ]+)\b/i', $this->userAgent, $matches)) return trim($matches[1]);
        // OnePlus
        if (preg_match('/\b(ONEPLUS A[0-9]{4}|OnePlus[a-zA-Z0-9 ]+)\b/i', $this->userAgent, $matches)) return trim($matches[1]);
        // Xiaomi / POCO / Redmi
        if (preg_match('/\b(Redmi [a-zA-Z0-9 ]+|POCO [a-zA-Z0-9 ]+|Mi [a-zA-Z0-9 ]+)\b/i', $this->userAgent, $matches)) return trim($matches[1]);
        // Huawei
        if (preg_match('/\b(HUAWEI [A-Z0-9\-]+|VOG-[L0-9A-Z]+)\b/i', $this->userAgent, $matches)) return trim($matches[1]);
        // Oppo
        if (preg_match('/\b(CPH[0-9]{4})\b/i', $this->userAgent, $matches)) return $matches[1];
        // Vivo
        if (preg_match('/\b(V[0-9]{4})\b/i', $this->userAgent, $matches)) return $matches[1];

        // Apple Devices
        if (preg_match('/\b(iPhone|iPad)\b/i', $this->userAgent, $matches)) return $matches[1];

        return null;
    }

    public function isBot(): bool
    {
        return $this->getBotName() !== null;
    }

    public function getBotName(): ?string
    {
        $bots = [
            'Googlebot'       => 'Googlebot',
            'Bingbot'         => 'bingbot',
            'AhrefsBot'       => 'AhrefsBot',
            'SemrushBot'      => 'SemrushBot',
            'YandexBot'       => 'YandexBot',
            'Facebook Crawler'=> 'facebookexternalhit',
            'Twitterbot'      => 'Twitterbot',
            'Applebot'        => 'Applebot',
            'DuckDuckBot'     => 'DuckDuckBot',
            'Baiduspider'     => 'Baiduspider',
        ];

        foreach ($bots as $name => $regex) {
            if (preg_match("/{$regex}/i", $this->userAgent)) {
                return $name;
            }
        }
        
        // Generic bot check
        if (preg_match('/bot|crawler|spider|crawling/i', $this->userAgent)) {
            return 'Generic Bot';
        }

        return null;
    }
}
