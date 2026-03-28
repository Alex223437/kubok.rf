<?php

namespace App\Services\Parsers;

use Illuminate\Support\Facades\Http;

class KhlParser extends BaseParser
{
    // KHL data API — publicly accessible, stable JSON with team logos
    private const DATA_API = 'https://api-video.khl.ru/api/khl_site/data.json?application=khl_web';

    public function parse(string $url): array
    {
        $logos = $this->fetchTeamLogos();

        $crawler = $this->getCrawler($url);
        $data = [];

        $crawler->filter('table tbody tr')->each(function ($node) use (&$data, $logos) {
            $cellNodes = $node->filter('td, th');
            $cells = $cellNodes->each(function ($cell) {
                return trim($cell->text());
            });

            $count = count($cells);

            // Regular season format has 11-12 numeric columns starting with rank number.
            // Playoff format has match-date columns — skip those rows.
            if ($count < 11 || !is_numeric($cells[0])) {
                return;
            }

            $teamName = $cells[1];
            $logo = $logos[$teamName] ?? $logos[$this->normalizeTeamName($teamName)] ?? null;

            if ($count >= 12) {
                // 12-column format: Rank, Team, И, В, ВО, ВБ, ПБ, ПО, ПП, П, Ш, О
                $data[] = [
                    'Место'   => $cells[0],
                    'Клуб'    => $teamName,
                    'Логотип' => $logo,
                    'И'       => $cells[2],
                    'В'       => $cells[3],
                    'ВО'      => $cells[4],
                    'ВБ'      => $cells[5],
                    'ПБ'      => $cells[6],
                    'ПО'      => $cells[7],
                    'ПП'      => $cells[8],
                    'П'       => $cells[9],
                    'Ш'       => $cells[10],
                    'О'       => end($cells),
                ];
            } else {
                // 11-column format: Rank, Team, И, В, ВО, ВБ, ПБ, ПО, П, Ш, О
                $data[] = [
                    'Место'   => $cells[0],
                    'Клуб'    => $teamName,
                    'Логотип' => $logo,
                    'И'       => $cells[2],
                    'В'       => $cells[3],
                    'ВО'      => $cells[4],
                    'ВБ'      => $cells[5],
                    'ПБ'      => $cells[6],
                    'ПО'      => $cells[7],
                    'ПП'      => null,
                    'П'       => $cells[8],
                    'Ш'       => $cells[9],
                    'О'       => end($cells),
                ];
            }
        });

        // Return empty if this looks like playoff data (no regular season rows found)
        return array_slice($data, 0, 10);
    }

    /**
     * Fetch team logos from the stable KHL data API.
     * Returns [ teamName => logoUrl ]
     * Public so the command can update logos independently.
     */
    public function fetchTeamLogos(): array
    {
        try {
            $response = Http::timeout(10)->get(self::DATA_API);
            if (!$response->successful()) {
                return [];
            }

            $logos = [];
            $teams = $response->json('teams') ?? [];
            foreach ($teams as $team) {
                if (!empty($team['name']) && !empty($team['image'])) {
                    $logos[$team['name']] = $team['image'];
                }
            }

            return $logos;
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Normalize team name for fuzzy logo matching.
     * e.g. "Динамо МН" → "Динамо Мн"
     */
    public function normalizeTeamName(string $name): string
    {
        return mb_convert_case(mb_strtolower($name, 'UTF-8'), MB_CASE_TITLE, 'UTF-8');
    }
}
