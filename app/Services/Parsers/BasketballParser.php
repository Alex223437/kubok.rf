<?php

namespace App\Services\Parsers;

use Illuminate\Support\Facades\Http;

class BasketballParser extends BaseParser
{
    // Секции с турнирными таблицами (RoundRobin)
    private const STANDINGS_SECTIONS = ['Регулярный чемпионат', 'Игры за 5-8 места', 'Игры за 11-14 места', 'Игры за 9-12 места', 'Игры за 5-6 места'];

    // Секции с плей-офф сеткой
    private const PLAYOFF_SECTIONS = ['Плейофф', 'Плей-ин'];

    public function parse(string $tag): array
    {
        $json = $this->fetchJsonPublic($tag);
        return $this->parseStandings($json);
    }

    public function parsePlayoff(string $tag): array
    {
        $json = $this->fetchJsonPublic($tag);
        return $this->parsePlayoffPairs($json);
    }

    public function fetchJsonPublic(string $tag): array
    {
        $headers = [
            'User-Agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
            'Accept'     => 'application/json, text/plain, */*',
            'Origin'     => 'https://russiabasket.ru',
            'Referer'    => 'https://russiabasket.ru/',
        ];

        // Сначала пробуем actual-standings
        $url = "https://bb.sportoteka.org/api/abc/comps/actual-standings?tag={$tag}&season=2026";
        $response = Http::withHeaders($headers)->get($url);
        if (!$response->successful()) {
            throw new \Exception("Failed to fetch JSON from API [{$url}]. Status: " . $response->status());
        }

        $json = $response->json();

        // Если регулярного чемпионата нет — пробуем /standings (содержит завершённые этапы)
        $hasRegular = collect($json['items'] ?? [])->contains(fn($i) =>
            ($i['comp']['compType'] ?? '') === 'RoundRobin' &&
            str_contains($i['comp']['name'] ?? '', 'Регулярный')
        );

        if (!$hasRegular) {
            $urlFull = "https://bb.sportoteka.org/api/abc/comps/standings?tag={$tag}&season=2026";
            $responseFull = Http::withHeaders($headers)->get($urlFull);
            if ($responseFull->successful()) {
                $jsonFull = $responseFull->json();
                // Берём регулярку из /standings и объединяем с остальным из /actual-standings
                $regularItems = collect($jsonFull['items'] ?? [])->filter(fn($i) =>
                    ($i['comp']['compType'] ?? '') === 'RoundRobin' &&
                    str_contains($i['comp']['name'] ?? '', 'Регулярный')
                )->values()->all();
                $json['items'] = array_merge($regularItems, $json['items'] ?? []);
            }
        }

        return $json;
    }

    private function parseStandings(array $json): array
    {
        $data = [];
        $seenTeams = [];

        if (!isset($json['items']) || !is_array($json['items'])) {
            return $data;
        }

        foreach ($json['items'] as $item) {
            $compName = $item['comp']['name'] ?? '';
            $compType = $item['comp']['compType'] ?? '';

            // Только RoundRobin секции (регулярка + игры за места)
            if ($compType !== 'RoundRobin') continue;

            $standings = $item['standings'] ?? [];
            if (empty($standings)) continue;

            foreach ($standings as $teamData) {
                $teamName = $teamData['name'] ?? '';
                if (empty($teamName) || in_array($teamName, $seenTeams)) continue;
                $seenTeams[] = $teamName;

                $totalGames = (int)($teamData['totalGames'] ?? 0);
                $totalWin   = (int)($teamData['totalWin'] ?? 0);
                $winPct     = $totalGames > 0 ? round($totalWin / $totalGames * 100, 1) : 0;

                $plus  = (int)($teamData['totalGoalPlus'] ?? 0);
                $minus = (int)($teamData['totalGoalMinus'] ?? 0);
                $diff  = $plus - $minus;

                // Последние 5 сыгранных матчей — W/L с точки зрения этой команды
                $last5 = [];
                $teamId = $teamData['teamId'] ?? null;
                $scores = $teamData['scores'] ?? [];
                $finished = array_filter($scores, fn($g) => ($g['gameStatus'] ?? '') === 'Result');
                foreach (array_slice($finished, -5) as $game) {
                    $isTeam1 = $game['team1id'] == $teamId;
                    $won = ($isTeam1 && $game['winTeam'] == 1) || (!$isTeam1 && $game['winTeam'] == 2);
                    $last5[] = $won ? 'W' : 'L';
                }

                $data[] = [
                    'section'    => $compName,
                    'Место'      => $teamData['place'] ?? '',
                    'Команда'    => $teamName,
                    'Логотип'    => $teamData['logo'] ?? null,
                    'Регион'     => $teamData['regionName'] ?? null,
                    'И'          => $totalGames,
                    'В'          => $totalWin,
                    '%'          => $winPct,
                    'П'          => (int)($teamData['totalDefeat'] ?? 0),
                    'О'          => (int)($teamData['totalPoints'] ?? 0),
                    '+'          => $plus,
                    '-'          => $minus,
                    'Разница'    => $diff > 0 ? "+{$diff}" : (string)$diff,
                    'Последние 5'=> implode(', ', $last5),
                ];
            }
        }

        return $data;
    }

    public function parsePlayoffPairs(array $json): array
    {
        $data = [];

        if (!isset($json['items']) || !is_array($json['items'])) {
            return $data;
        }

        foreach ($json['items'] as $item) {
            $compName = $item['comp']['name'] ?? '';
            $compType = $item['comp']['compType'] ?? '';

            if ($compType !== 'Playoff') continue;

            $pairs = $item['playoffPairs'] ?? [];
            if (empty($pairs)) continue;

            // Определяем slug секции
            $section = match(true) {
                str_contains($compName, 'Плей-ин') || str_contains($compName, 'Плейин') => 'playin',
                str_contains($compName, '5-8')  => '5-8',
                str_contains($compName, '11-14') => '11-14',
                str_contains($compName, '9-12')  => '9-12',
                default => 'playoff',
            };

            foreach ($pairs as $pair) {
                $team1 = $pair['team1'] ?? [];
                $team2 = $pair['team2'] ?? [];

                // Игры пары
                $games = [];
                foreach ($pair['scores'] ?? [] as $game) {
                    $games[] = [
                        'date'   => $game['gameDate'] ?? null,
                        'score'  => $game['score'] ?? null,
                        'status' => $game['gameStatus'] ?? null,
                    ];
                }

                $data[] = [
                    'section'      => $section,
                    'section_name' => $compName,
                    'round'        => (int)($pair['round'] ?? 0),
                    'sort'         => (int)($pair['sort'] ?? 0),
                    'team1_name'   => $team1['name'] ?? null,
                    'team1_logo'   => $team1['logo'] ?? null,
                    'team1_region' => $team1['regionName'] ?? null,
                    'team2_name'   => $team2['name'] ?? null,
                    'team2_logo'   => $team2['logo'] ?? null,
                    'team2_region' => $team2['regionName'] ?? null,
                    'score1'       => isset($pair['score1']) && $pair['score1'] !== null ? (int)$pair['score1'] : null,
                    'score2'       => isset($pair['score2']) && $pair['score2'] !== null ? (int)$pair['score2'] : null,
                    'winner'       => (int)($pair['winner'] ?? 0),
                    'games'        => $games,
                ];
            }
        }

        return $data;
    }
}
