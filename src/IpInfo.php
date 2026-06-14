<?php

namespace Parvion\IpIntelligence;

use Illuminate\Http\Request;

class IpInfo extends IpIntelligence
{
    /**
     * Create a new IpInfo instance.
     *
     * @param  \Illuminate\Http\Request|null  $request
     * @return void
     */
    public function __construct(?Request $request = null)
    {
        parent::__construct($request);
    }
}
