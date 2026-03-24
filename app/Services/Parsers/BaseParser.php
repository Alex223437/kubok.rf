<?php

namespace App\Services\Parsers;

use Illuminate\Support\Facades\Http;
use Symfony\Component\DomCrawler\Crawler;
use App\Services\Parsers\Contracts\LeagueParserInterface;

abstract class BaseParser implements LeagueParserInterface
{
    /**
     * Fetch HTML from a URL and return a DomCrawler instance.
     *
     * @param string $url
     * @return Crawler
     * @throws \Exception
     */
    protected function getCrawler(string $url): Crawler
    {
        $response = Http::withHeaders([
            'User-Agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
            'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
            'Accept-Language' => 'ru-RU,ru;q=0.9,en-US;q=0.8,en;q=0.7'
        ])->get($url);

        if (!$response->successful()) {
            throw new \Exception("Failed to fetch HTML from URL [{$url}]. Status: " . $response->status());
        }

        // Convert encoding if needed, or just pass the body directly.
        $html = $response->body();

        $crawler = new Crawler();
        $crawler->addHtmlContent($html, 'UTF-8');

        return $crawler;
    }
}
