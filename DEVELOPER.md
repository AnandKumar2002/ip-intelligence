# Developer Documentation & Architecture Guide

Welcome to the internal developer guide for `parvion/ip-intelligence`. This document is specifically written to help you (or future developers) instantly understand the package's architecture, data flow, and exactly where to look when you need to debug or add new features.

---

## 🏛️ 1. High-Level Architecture

This package is built using a highly modular, decoupled architecture. Instead of having one massive class with 2,000 lines of code, everything is broken down into small, single-responsibility **Traits (Concerns)** and **Services**.

When a user calls `ip_info()->country()`, here is the exact lifecycle:
1. The **Global Helper** (`ip_info()`) or **Facade** (`IpIntelligence`) resolves the singleton instance from the Laravel Container.
2. The core class `IpInfo` (which extends `IpIntelligence`) receives the request.
3. The method call is routed to the specific Trait responsible for that feature (e.g., `HasGeoLocation`).
4. If it requires heavy lifting (like API calls or Regex), the Trait delegates the work to a dedicated **Service** or **Parser**.

---

## 📂 2. File & Directory Breakdown

Here is exactly what every file in the `src/` directory does:

### The Core Entry Points
- **`IpIntelligenceServiceProvider.php`**: The absolute starting point. This tells Laravel to load our config file and binds the `ip-intelligence` singleton into Laravel's service container.
- **`helpers.php`**: Registers the global `ip_info()` function so developers don't have to import classes.
- **`Facades/IpIntelligence.php`**: Provides the static `IpIntelligence::city()` syntax by proxying calls to our underlying singleton.
- **`IpIntelligence.php` & `IpInfo.php`**: The main classes. They don't actually hold much logic! Instead, they combine all the Traits together and implement the `Arrayable` and `JsonSerializable` contracts so Laravel can easily convert them to JSON.

---

### The Logic (Concerns / Traits)
Located in `src/Concerns/`. These files hold the actual public methods the developer interacts with (like `->city()`, `->deviceType()`, `->isBot()`).

- **`HasIpDetection.php`**: Figures out the visitor's real IP address. It knows how to look through proxy headers (`HTTP_CLIENT_IP`, `HTTP_X_FORWARDED_FOR`) to find the true IP. It also contains the `$info->lookup('8.8.8.8')` logic.
- **`HasGeoLocation.php`**: Contains all the location methods (`country()`, `city()`, `currency()`, etc.). It acts as a bridge to the `GeoLocationService`.
- **`HasDeviceDetection.php`**: Contains all the device methods (`device()`, `browser()`, `platform()`, etc.). It acts as a bridge to the `UserAgentParser`.
- **`HasSecurityDetection.php`**: Contains the logic to detect if the IP is a known Bot, Tor exit node, VPN, or Proxy. It does this by checking the User-Agent against arrays of known bot signatures, and checking the GeoLocation ISP names against known datacenter lists (like AWS, Google Cloud, DigitalOcean).
- **`HasNetworkDetection.php`**: Simply pulls the ASN and ISP data out of the geolocation results.

---

### The Engines (Services & Parsers)
These files do the actual heavy lifting. If you need to fix a bug in detection, you will almost certainly be editing one of these files.

- **`Parsers/UserAgentParser.php`**: This is the heart of the device detection. It takes the raw User-Agent string (e.g., `Mozilla/5.0...`) and runs it through extremely fast, custom Regular Expressions (Regex) to extract the Brand, OS, Architecture, Browser, and Engine. *If a specific mobile phone isn't being detected properly, you add its Regex here!*
- **`Services/GeoLocationService.php`**: Manages the caching layer. It checks if an IP has been searched recently. If yes, it pulls from Laravel's Cache. If no, it asks the Provider.
- **`Services/Providers/IpApiProvider.php`**: The actual HTTP client that reaches out to `http://ip-api.com`. *If you ever want to add a different API (like MaxMind or IPData), you would create a new Provider here.*
- **`Contracts/GeoLocationProvider.php`**: The Interface that guarantees all future API Providers have a `lookup(string $ip): array` method.

---

## 🐛 3. How to Debug (Cheat Sheet)

If you run into an issue, use this quick cheat sheet to know exactly which file to open:

| Issue | File to Edit |
|-------|--------------|
| A specific Phone Model isn't detected | `src/Parsers/UserAgentParser.php` |
| Chrome/Firefox engine is returning null | `src/Parsers/UserAgentParser.php` |
| It thinks a real user is a GoogleBot | `src/Concerns/HasSecurityDetection.php` |
| I want to add a new `continent()` feature | `src/Services/Providers/IpApiProvider.php` (add to API response array) AND `src/Concerns/HasGeoLocation.php` (add the method) |
| Cloudflare IPs are returning the wrong IP | `src/Concerns/HasIpDetection.php` |
| Caching isn't working properly | `src/Services/GeoLocationService.php` |
| A Laravel route says class not found | Run `composer dump-autoload` |

## 🛠️ 4. Adding New Features

Because of the modular architecture, adding new features is incredibly easy:
1. **Find the right Trait**: If you are adding a feature about Hardware, open `HasDeviceDetection`. If it's about network routing, open `HasNetworkDetection`.
2. **Write the method**: Add your new `public function myFeature()` directly into the trait.
3. **Update the Main Array**: Open `IpIntelligence.php` and add your new method to the `all()` method's return array so it shows up in JSON exports.
4. **Update the README**: Don't forget to document it!
