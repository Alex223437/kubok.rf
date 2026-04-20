<?php

namespace App\Services\Parsers;

use App\Models\Option;
use Illuminate\Support\Facades\Http;

class KhlParser extends BaseParser
{
    // KHL data API — publicly accessible, stable JSON with team logos
    private const DATA_API = 'https://api-video.khl.ru/api/khl_site/data.json?application=khl_web';

    // Статический маппинг команд → конференция / дивизион (сезон 2025-26)
    // Источник: структура dropdown на khl.ru/standings/
    private const DIVISION_MAP = [
        // === Западная конференция — Дивизион Боброва ===
        'СКА'           => ['conference' => 'Западная', 'division' => 'Боброва'],
        'Спартак'       => ['conference' => 'Западная', 'division' => 'Боброва'],
        'Торпедо'       => ['conference' => 'Западная', 'division' => 'Боброва'],
        'ХК Сочи'       => ['conference' => 'Западная', 'division' => 'Боброва'],
        'Лада'          => ['conference' => 'Западная', 'division' => 'Боброва'],
        // === Западная конференция — Дивизион Тарасова ===
        'ЦСКА'          => ['conference' => 'Западная', 'division' => 'Тарасова'],
        'Динамо М'      => ['conference' => 'Западная', 'division' => 'Тарасова'],
        'Локомотив'     => ['conference' => 'Западная', 'division' => 'Тарасова'],
        'Северсталь'    => ['conference' => 'Западная', 'division' => 'Тарасова'],
        'Динамо Мн'     => ['conference' => 'Западная', 'division' => 'Тарасова'],
        'Драконы'       => ['conference' => 'Западная', 'division' => 'Тарасова'],
        // === Восточная конференция — Дивизион Харламова ===
        'Металлург Мг'  => ['conference' => 'Восточная', 'division' => 'Харламова'],
        'Ак Барс'       => ['conference' => 'Восточная', 'division' => 'Харламова'],
        'Автомобилист'  => ['conference' => 'Восточная', 'division' => 'Харламова'],
        'Нефтехимик'    => ['conference' => 'Восточная', 'division' => 'Харламова'],
        'Трактор'       => ['conference' => 'Восточная', 'division' => 'Харламова'],
        // === Восточная конференция — Дивизион Чернышёва ===
        'Авангард'      => ['conference' => 'Восточная', 'division' => 'Чернышёва'],
        'Сибирь'        => ['conference' => 'Восточная', 'division' => 'Чернышёва'],
        'Амур'          => ['conference' => 'Восточная', 'division' => 'Чернышёва'],
        'Адмирал'       => ['conference' => 'Восточная', 'division' => 'Чернышёва'],
        'Барыс'         => ['conference' => 'Восточная', 'division' => 'Чернышёва'],
        'Салават Юлаев' => ['conference' => 'Восточная', 'division' => 'Чернышёва'],
    ];

    public function parse(string $url): array
    {
        $logos = $this->fetchTeamLogos();
        $data  = $this->parseStandingsFromUrl($url, $logos);

        // В плей-офф основной URL показывает сетку — пробуем URL регулярного чемпионата
        if (empty($data)) {
            $seasonId = Option::where('code', 'khl_season_id')->value('value');
            if ($seasonId) {
                $data = $this->parseStandingsFromUrl(
                    "https://www.khl.ru/standings/{$seasonId}/conference/",
                    $logos
                );
            }
        }

        return $data;
    }

    private function parseStandingsFromUrl(string $url, array $logos): array
    {
        $crawler = $this->getCrawler($url);
        $data = [];

        $seen = [];

        $crawler->filter('table tbody tr')->each(function ($node) use (&$data, &$seen, $logos) {
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

            // Page has multiple tables (overall + conference + division) — skip duplicates
            if (isset($seen[$teamName])) {
                return;
            }
            $seen[$teamName] = true;
            $logo = $logos[$teamName] ?? $logos[$this->normalizeTeamName($teamName)] ?? null;

            $divInfo = self::DIVISION_MAP[$teamName] ?? null;

            if ($count >= 12) {
                // 12-column format: Rank, Team, И, В, ВО, ВБ, ПБ, ПО, ПП, П, Ш, О
                $data[] = [
                    'Место'        => $cells[0],
                    'Клуб'         => $teamName,
                    'Логотип'      => $logo,
                    'Конференция'  => $divInfo['conference'] ?? null,
                    'Дивизион'     => $divInfo['division'] ?? null,
                    'И'            => $cells[2],
                    'В'            => $cells[3],
                    'ВО'           => $cells[4],
                    'ВБ'           => $cells[5],
                    'ПБ'           => $cells[6],
                    'ПО'           => $cells[7],
                    'ПП'           => $cells[8],
                    'П'            => $cells[9],
                    'Ш'            => $cells[10],
                    'О'            => end($cells),
                ];
            } else {
                // 11-column format: Rank, Team, И, В, ВО, ВБ, ПБ, ПО, П, Ш, О
                $data[] = [
                    'Место'        => $cells[0],
                    'Клуб'         => $teamName,
                    'Логотип'      => $logo,
                    'Конференция'  => $divInfo['conference'] ?? null,
                    'Дивизион'     => $divInfo['division'] ?? null,
                    'И'            => $cells[2],
                    'В'            => $cells[3],
                    'ВО'           => $cells[4],
                    'ВБ'           => $cells[5],
                    'ПБ'           => $cells[6],
                    'ПО'           => $cells[7],
                    'ПП'           => null,
                    'П'            => $cells[8],
                    'Ш'            => $cells[9],
                    'О'            => end($cells),
                ];
            }
        });

        return $data;
    }

    /**
     * Return conference/division info for a team by name.
     */
    public function getDivisionInfo(string $teamName): ?array
    {
        return self::DIVISION_MAP[$teamName] ?? null;
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
     * Fetch current stage ID from the KHL data API.
     */
    public function fetchCurrentStageId(): ?int
    {
        try {
            $response = Http::timeout(10)->get(self::DATA_API);
            return $response->successful() ? (int)$response->json('current_stage_id') : null;
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Fetch upcoming KHL matches for the given stage.
     * Returns array of match data suitable for UpcomingMatch model.
     */
    public function fetchUpcomingMatches(?int $stageId = null): array
    {
        if (!$stageId) {
            $stageId = $this->fetchCurrentStageId();
        }
        if (!$stageId) {
            return [];
        }

        try {
            $url = 'https://webcaster.pro/api/khl_mobile/events_v2.json';
            $response = Http::withHeaders([
                'User-Agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
                'Accept'     => 'application/json',
                'Referer'    => 'https://www.khl.ru/',
                'Origin'     => 'https://www.khl.ru',
            ])->timeout(15)->get($url, [
                'q[stage_id_eq]'           => $stageId,
                'q[game_state_key_in][]'   => 'not_yet_started',
            ]);

            if (!$response->successful()) {
                return [];
            }

            $items = $response->json() ?? [];
            $matches = [];

            foreach ($items as $item) {
                $event = $item['event'] ?? $item;
                if (empty($event['start_at'])) continue;

                $ts    = (int)($event['start_at'] / 1000);
                $dt    = new \DateTime('@' . $ts);
                $team1 = $event['team_a']['name'] ?? null;
                $team2 = $event['team_b']['name'] ?? null;
                if (!$team1 || !$team2) continue;

                $matches[] = [
                    'league_name' => $event['stage_name'] ?? 'КХЛ',
                    'team1'       => $team1,
                    'team1_logo'  => $event['team_a']['image'] ?? null,
                    'team2'       => $team2,
                    'team2_logo'  => $event['team_b']['image'] ?? null,
                    'match_at'    => $dt->format('Y-m-d H:i:s'),
                ];
            }

            // Sort by match_at ascending
            usort($matches, fn($a, $b) => strcmp($a['match_at'], $b['match_at']));

            return $matches;
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
