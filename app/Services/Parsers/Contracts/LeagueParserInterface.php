<?php

namespace App\Services\Parsers\Contracts;

interface LeagueParserInterface
{
    /**
     * Parse the given URL and return an array of structured data.
     *
     * @param string $url
     * @return array
     */
    public function parse(string $url): array;
}
