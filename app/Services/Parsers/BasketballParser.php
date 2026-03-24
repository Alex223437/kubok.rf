<?php

namespace App\Services\Parsers;

use Illuminate\Support\Facades\Http;

class BasketballParser extends BaseParser
{
    public function parse(string $tag): array
    {
        // Instead of parsing the HTML, the Basketball SPA loads data via an API.
        // We use the tag parameter (msl, wsl, etc.) passed into the function.
        $apiUrl = "https://bb.sportoteka.org/api/abc/comps/actual-standings?tag={$tag}&season=2026";

        $response = Http::withHeaders([
            'User-Agent' => 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
            'Accept' => 'application/json, text/plain, */*',
            'Origin' => 'https://russiabasket.ru',
            'Referer' => 'https://russiabasket.ru/'
        ])->get($apiUrl);

        if (!$response->successful()) {
            throw new \Exception("Failed to fetch JSON from API [{$apiUrl}]. Status: " . $response->status());
        }

        $json = $response->json();
        $data = [];

        // Navigate the JSON response structure
        // The API returns an array of "items" representing different phases or groups.
        if (isset($json['items']) && is_array($json['items'])) {
            // Find the item with the most teams in standings to represent the overall table, 
            // or just take the first one with non-empty standings. Let's merge all active standings or just take the first meaningful one.
            // Some leagues split standings into "Places 1-6" and "Places 7-12".
            // Let's iterate all items and collect teams, keeping track of names so we don't duplicate.
            $seenTeams = [];

            foreach ($json['items'] as $item) {
                if (isset($item['standings']) && is_array($item['standings']) && !empty($item['standings'])) {
                    foreach ($item['standings'] as $teamData) {
                        $teamName = is_array($teamData['name'] ?? null) ? json_encode($teamData['name']) : ($teamData['name'] ?? '');

                        if (empty($teamName) || in_array($teamName, $seenTeams)) {
                            continue;
                        }
                        $seenTeams[] = $teamName;

                        // Extract last 5 matches
                        $last5 = [];
                        if (isset($teamData['scores']) && is_array($teamData['scores'])) {
                            $finishedGames = array_filter($teamData['scores'], function ($game) {
                                return isset($game['gameStatus']) && $game['gameStatus'] === 'Result';
                            });
                            $recentGames = array_slice($finishedGames, -5);
                            foreach ($recentGames as $game) {
                                $score = is_array($game['score'] ?? null) ? json_encode($game['score']) : ($game['score'] ?? '');
                                $last5[] = str_replace(' ', '', $score);
                            }
                        }

                        $plus = is_array($teamData['totalGoalPlus'] ?? 0) ? 0 : ($teamData['totalGoalPlus'] ?? 0);
                        $minus = is_array($teamData['totalGoalMinus'] ?? 0) ? 0 : ($teamData['totalGoalMinus'] ?? 0);
                        $diff = $plus - $minus;

                        $place = is_array($teamData['place'] ?? '') ? '' : ($teamData['place'] ?? '');
                        $totalGames = is_array($teamData['totalGames'] ?? '') ? '' : ($teamData['totalGames'] ?? '');
                        $totalWin = is_array($teamData['totalWin'] ?? '') ? '' : ($teamData['totalWin'] ?? '');
                        $totalDefeat = is_array($teamData['totalDefeat'] ?? '') ? '' : ($teamData['totalDefeat'] ?? '');
                        $totalPoints = is_array($teamData['totalPoints'] ?? '') ? '' : ($teamData['totalPoints'] ?? '');

                        $data[] = [
                            'Место' => $place,
                            'Команда' => $teamName,
                            'И' => $totalGames,
                            'В' => $totalWin,
                            'П' => $totalDefeat,
                            'О' => $totalPoints,
                            '+/-' => "{$plus}/{$minus}",
                            'Разница' => $diff > 0 ? "+{$diff}" : (string) $diff,
                            'Последние 5' => implode(', ', $last5),
                        ];
                    }
                }
            }
        }

        return $data;
    }
}
