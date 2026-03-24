<?php

namespace App\Services\Parsers;

class RfsCupParser extends BaseParser
{
    public function parse(string $url): array
    {
        $crawler = $this->getCrawler($url);
        $data = [];

        $crawler->filter('.bet-tournament-table .playoff-match-cont')->each(function ($node) use (&$data) {
            $team1 = $node->filter('.team1 .playoff-match-team-title span')->count() ? $node->filter('.team1 .playoff-match-team-title span')->text() : '';
            $team2 = $node->filter('.team2 .playoff-match-team-title span')->count() ? $node->filter('.team2 .playoff-match-team-title span')->text() : '';

            $scoresAndDates = $node->filter('.playoff-match-score a')->each(function ($scoreNode) {
                $score = $scoreNode->filter('.score')->count() ? trim($scoreNode->filter('.score')->text()) : '';
                $date = $scoreNode->filter('.date-time')->count() ? trim(preg_replace('/\s+/', ' ', $scoreNode->filter('.date-time')->text())) : '';
                return $score ?: $date;
            });

            // Filter out empty results from spaces
            $scoresAndDates = array_filter($scoresAndDates);

            if ($team1 || $team2) {
                // Avoid duplicates if multiple nodes match identically
                $entry = [
                    'team1' => trim($team1),
                    'team2' => trim($team2),
                    'score_or_date' => implode(' | ', $scoresAndDates)
                ];
                if (!in_array($entry, $data)) {
                    $data[] = $entry;
                }
            }
        });

        return $data;
    }
}
