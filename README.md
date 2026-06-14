# Laravel IP Intelligence

[![PHP Version](https://img.shields.io/badge/php-%5E8.0-8892BF)](https://php.net)
[![Laravel](https://img.shields.io/badge/laravel-10%20%7C%2011%20%7C%2012%20%7C%2013-FF2D20?logo=laravel&logoColor=white)](https://laravel.com)
[![License: MIT](https://img.shields.io/badge/License-MIT-22c55e.svg)](LICENSE)

> A robust, ultra-fast, and zero-dependency Laravel package that collects and analyzes visitor information from Request IPs and User Agents.

This package provides deep insights into your visitors, featuring built-in geolocation, device detection, browser parsing, bot detection, VPN/Proxy detection, and more—all without relying on heavy third-party device libraries like `Mobile_Detect`.

---

## 🌟 Core Features

- **Zero Third-Party Dependencies:** Custom-built parsers ensure maximum performance without the bloat of massive external libraries.
- **Advanced Geolocation:** Instantly detects Continents, Countries, States, Cities, Timezones, Zip Codes, ISPs, and ASNs.
- **Smart Device Detection:** Accurately identifies exact device platforms (Windows, macOS, iOS, Android), architectures (x64, ARM), types (Desktop, Phone, Tablet), brands (Apple, Samsung, Xiaomi), and models.
- **Browser & Engine Parsing:** Detects the browser name, version, and rendering engine (Blink, WebKit, Gecko, Trident).
- **Security & Bot Detection:** Sniffs out known web crawlers, Tor exit nodes, proxies, VPNs, and suspicious network topologies.
- **Automated Caching:** Geolocation requests are automatically cached using Laravel's Cache to prevent rate limits and boost speed.

---

## 📦 Installation

You can install the package via composer:

```bash
composer require parvion/ip-intelligence
```

*(Optional)* You can publish the configuration file to customize the caching and geolocation providers:

```bash
php artisan vendor:publish --provider="Parvion\IpIntelligence\IpIntelligenceServiceProvider"
```

---

## 🚀 How to Use

The package gives you an elegant Facade (`IpIntelligence`) and a global helper (`ip_info()`) to access all intelligence seamlessly.

### 1. Get the Full Intelligence Profile

You can retrieve a comprehensive JSON-ready array of everything detected about the current visitor using the Facade, the global helper, or direct Object Instantiation.

**Using Object Instantiation:**
```php
use Parvion\IpIntelligence\IpInfo;

$info = new IpInfo();
$profile = $info->all();
```

**Using the Facade:**
```php
use Parvion\IpIntelligence\Facades\IpIntelligence;

$profile = IpIntelligence::all();
```

**Using the Global Helper:**
```php
$profile = ip_info()->all();
```

**Example Output:**
```json
{
    "ip": "8.8.8.8",
    "location": {
        "country": "United States",
        "country_code": "US",
        "state": "Virginia",
        "city": "Ashburn",
        "zip_code": "20149",
        "latitude": 39.03,
        "longitude": -77.5,
        "timezone": "America/New_York",
        "currency": "USD",
        "isp": "Google LLC",
        "organization": "Google Public DNS",
        "asn": "AS15169 Google LLC"
    },
    "user_agent": "Mozilla/5.0 (iPhone; CPU iPhone OS 16_0 like Mac OS X)...",
    "browser": {
        "name": "Safari",
        "version": "16.0",
        "engine": "WebKit"
    },
    "device": {
        "type": "Mobile",
        "name": "iPhone",
        "brand": "Apple",
        "model": "iPhone"
    },
    "platform": {
        "name": "iOS",
        "version": "16.0",
        "architecture": "ARM64"
    },
    "security": {
        "is_bot": false,
        "bot_name": null,
        "is_vpn": false,
        "is_proxy": false,
        "is_tor": false,
        "is_suspicious": false
    },
    "network": {
        "isp": "Google LLC",
        "asn": "AS15169 Google LLC",
        "organization": "Google Public DNS",
        "hosting": null
    },
    "language": "en-US",
    "referrer": "https://google.com"
}
```

---

### 2. Individual Feature Usage

If you don't need the entire profile, you can call specific methods individually:

#### IP & Location Detection
```php
IpIntelligence::ip();             // Returns current Request IP (e.g., '192.168.1.1')
IpIntelligence::country();        // 'United States'
IpIntelligence::city();           // 'New York'
IpIntelligence::timezone();       // 'America/New_York'
IpIntelligence::currency();       // 'USD'
```

#### Custom IP Lookup
You can lookup a specific IP address instead of using the current Request IP:
```php
IpIntelligence::lookup('8.8.8.8')->city(); 
```

#### Device & Browser Parsing
```php
IpIntelligence::deviceType();     // 'Desktop', 'Phone', 'Tablet'
IpIntelligence::deviceBrand();    // 'Apple', 'Samsung', 'Xiaomi', etc.
IpIntelligence::platform();       // 'Windows', 'macOS', 'iOS', 'Android'
IpIntelligence::browser();        // 'Chrome', 'Firefox', 'Safari'
IpIntelligence::browserEngine();  // 'Blink', 'WebKit', 'Gecko'
```

#### Security & Network
```php
IpIntelligence::isBot();          // bool
IpIntelligence::botName();        // 'Googlebot', 'Bingbot', null
IpIntelligence::isVpn();          // bool
IpIntelligence::isProxy();        // bool
IpIntelligence::isTor();          // bool
IpIntelligence::network();        // Returns ISP and ASN information array
```



---

## 🛡 License

This package is open-source software licensed under the [MIT license](LICENSE).

Copyright (c) 2026 Anand Kumar (Parvion)
