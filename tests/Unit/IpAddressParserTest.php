<?php

namespace Parvion\IpIntelligence\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Illuminate\Http\Request;
use Parvion\IpIntelligence\Parsers\IpAddressParser;

class IpAddressParserTest extends TestCase
{
    public function test_it_gets_ip_from_cf_connecting_ip()
    {
        $request = Request::create('/', 'GET', [], [], [], [
            'HTTP_CF_CONNECTING_IP' => '1.2.3.4',
            'REMOTE_ADDR' => '127.0.0.1'
        ]);

        $this->assertEquals('1.2.3.4', IpAddressParser::parse($request));
    }

    public function test_it_gets_first_ip_from_x_forwarded_for()
    {
        $request = Request::create('/', 'GET', [], [], [], [
            'HTTP_X_FORWARDED_FOR' => '8.8.8.8, 10.0.0.1, 192.168.1.1',
            'REMOTE_ADDR' => '127.0.0.1'
        ]);

        $this->assertEquals('8.8.8.8', IpAddressParser::parse($request));
    }

    public function test_it_falls_back_to_remote_addr()
    {
        $request = Request::create('/', 'GET', [], [], [], [
            'REMOTE_ADDR' => '9.9.9.9'
        ]);

        $this->assertEquals('9.9.9.9', IpAddressParser::parse($request));
    }
}
