<?php

namespace Parvion\IpIntelligence\Concerns;

use Parvion\IpIntelligence\Parsers\IpAddressParser;

trait HasIpDetection
{
    /**
     * The explicitly looked up IP address.
     *
     * @var string|null
     */
    protected $customIp = null;

    /**
     * Get the detected or looked up IP address.
     *
     * @return string|null
     */
    public function ip(): ?string
    {
        return $this->customIp ?? IpAddressParser::parse($this->request);
    }

    /**
     * Perform a custom lookup for a specific IP.
     *
     * @param string|null $ip
     * @return $this
     */
    public function lookup(?string $ip = null): self
    {
        if ($ip) {
            $this->customIp = $ip;
        }
        
        return $this;
    }
}
