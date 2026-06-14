<?php

namespace Parvion\IpIntelligence\Concerns;

trait HasNetworkDetection
{
    /**
     * Get Network Information.
     *
     * @return array
     */
    public function network(): array
    {
        $location = $this->location();

        return [
            'isp'          => $location['isp'] ?? null,
            'asn'          => $location['asn'] ?? null,
            'organization' => $location['organization'] ?? null,
            'hosting'      => $this->isVpn() ? ($location['organization'] ?? null) : null,
        ];
    }
}
