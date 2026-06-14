<?php

if (! function_exists('ip_info')) {
    /**
     * Get the IpIntelligence instance.
     *
     * @return \Parvion\IpIntelligence\IpIntelligence
     */
    function ip_info()
    {
        return app('ip-intelligence');
    }
}
