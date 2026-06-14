<?php

namespace Parvion\IpIntelligence\Parsers;

use Illuminate\Http\Request;

class HeaderParser
{
    /**
     * Get preferred language from request.
     *
     * @param \Illuminate\Http\Request $request
     * @return string|null
     */
    public static function parseLanguage(Request $request): ?string
    {
        return $request->getLanguages()[0] ?? null;
    }

    /**
     * Get referrer from request.
     *
     * @param \Illuminate\Http\Request $request
     * @return string|null
     */
    public static function parseReferrer(Request $request): ?string
    {
        return $request->headers->get('referer');
    }
}
