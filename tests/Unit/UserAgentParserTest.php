<?php

namespace Parvion\IpIntelligence\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Parvion\IpIntelligence\Parsers\UserAgentParser;

class UserAgentParserTest extends TestCase
{
    public function test_it_can_parse_windows_chrome_desktop()
    {
        $ua = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36';
        $parser = new UserAgentParser($ua);

        $this->assertEquals('Windows', $parser->getPlatform());
        $this->assertEquals('10/11', $parser->getPlatformVersion());
        $this->assertEquals('Chrome', $parser->getBrowser());
        $this->assertEquals('120.0.0.0', $parser->getBrowserVersion());
        $this->assertEquals('Desktop', $parser->getDeviceType());
        $this->assertFalse($parser->isBot());
    }

    public function test_it_can_parse_iphone_safari_mobile()
    {
        $ua = 'Mozilla/5.0 (iPhone; CPU iPhone OS 17_0 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.0 Mobile/15E148 Safari/604.1';
        $parser = new UserAgentParser($ua);

        $this->assertEquals('iPhone', $parser->getPlatform());
        $this->assertEquals('17.0', $parser->getPlatformVersion());
        $this->assertEquals('Safari', $parser->getBrowser());
        $this->assertEquals('17.0', $parser->getBrowserVersion());
        $this->assertEquals('Mobile', $parser->getDeviceType());
        $this->assertEquals('Apple', $parser->getDeviceBrand());
        $this->assertEquals('iPhone', $parser->getDeviceName());
    }

    public function test_it_can_parse_android_samsung_mobile()
    {
        $ua = 'Mozilla/5.0 (Linux; Android 13; SM-G998B) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/119.0.0.0 Mobile Safari/537.36';
        $parser = new UserAgentParser($ua);

        $this->assertEquals('Android', $parser->getPlatform());
        $this->assertEquals('13', $parser->getPlatformVersion());
        $this->assertEquals('Chrome', $parser->getBrowser());
        $this->assertEquals('119.0.0.0', $parser->getBrowserVersion());
        $this->assertEquals('Mobile', $parser->getDeviceType());
        $this->assertEquals('Samsung', $parser->getDeviceBrand());
        $this->assertEquals('SM-G998B', $parser->getDeviceModel());
    }

    public function test_it_can_detect_googlebot()
    {
        $ua = 'Mozilla/5.0 (compatible; Googlebot/2.1; +http://www.google.com/bot.html)';
        $parser = new UserAgentParser($ua);

        $this->assertTrue($parser->isBot());
        $this->assertEquals('Bot', $parser->getDeviceType());
        $this->assertEquals('Googlebot', $parser->getBotName());
    }

    public function test_it_can_parse_ipad_tablet()
    {
        $ua = 'Mozilla/5.0 (iPad; CPU OS 16_6 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.6 Mobile/15E148 Safari/604.1';
        $parser = new UserAgentParser($ua);

        $this->assertEquals('Tablet', $parser->getDeviceType());
        $this->assertEquals('iPad', $parser->getPlatform());
    }
}
