<?php

namespace Parvion\IpIntelligence\Concerns;

trait HasSecurityDetection
{
    /**
     * Check if the IP belongs to a VPN.
     * Uses hosting/org data from location lookup to guess VPNs.
     * Note: True VPN detection usually requires a premium database.
     *
     * @return bool
     */
    public function isVpn(): bool
    {
        $org = strtolower($this->location()['organization'] ?? '');
        $isp = strtolower($this->location()['isp'] ?? '');
        
        $vpnKeywords = ['vpn', 'proxy', 'hosting', 'datacenter', 'cloud', 'digitalocean', 'aws', 'amazon', 'linode', 'ovh', 'choopa', 'hetzner'];

        foreach ($vpnKeywords as $keyword) {
            if (str_contains($org, $keyword) || str_contains($isp, $keyword)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if the request is routed through a proxy.
     *
     * @return bool
     */
    public function isProxy(): bool
    {
        $proxyHeaders = [
            'HTTP_VIA',
            'HTTP_X_FORWARDED_FOR',
            'HTTP_FORWARDED_FOR',
            'HTTP_X_FORWARDED',
            'HTTP_FORWARDED',
            'HTTP_CLIENT_IP',
            'HTTP_FORWARDED_FOR_IP',
            'VIA',
            'X_FORWARDED_FOR',
            'FORWARDED_FOR',
            'X_FORWARDED',
            'FORWARDED',
            'CLIENT_IP',
            'FORWARDED_FOR_IP',
            'HTTP_PROXY_CONNECTION'
        ];

        foreach ($proxyHeaders as $header) {
            if ($this->request->server($header)) {
                return true;
            }
        }

        return $this->isVpn();
    }

    /**
     * Check if the IP is a known TOR exit node.
     * In a robust implementation, this would check against a cached Tor list.
     *
     * @return bool
     */
    public function isTor(): bool
    {
        $org = strtolower($this->location()['organization'] ?? '');
        return str_contains($org, 'tor exit') || str_contains($org, 'tor network');
    }

    /**
     * Check if request is suspicious (Bot, Proxy, or VPN).
     *
     * @return bool
     */
    public function isSuspicious(): bool
    {
        return $this->isBot() || $this->isProxy() || $this->isVpn() || $this->isTor();
    }
}
