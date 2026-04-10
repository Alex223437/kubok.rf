<?php

namespace App\Services\Parsers;

class RfsCupParser extends BaseParser
{
    // Групповой этап (Путь РПЛ)
    private const GROUP_ROUNDS = [
        'Путь РПЛ. Группа А' => 18963,
        'Путь РПЛ. Группа B' => 18962,
        'Путь РПЛ. Группа C' => 18961,
        'Путь РПЛ. Группа D' => 18960,
    ];

    // Плей-офф (Путь РПЛ)
    private const PLAYOFF_ROUNDS = [
        'Путь РПЛ. Плей-офф' => 18967,
    ];

    // Раунды (Путь регионов)
    private const REGIONS_ROUNDS = [
        'Путь регионов. Раунд 1' => 18953,
        'Путь регионов. Раунд 2' => 18954,
        'Путь регионов. Раунд 3' => 18955,
        'Путь регионов. Раунд 4' => 18956,
        'Путь регионов. Раунд 5' => 18957,
        'Путь регионов. Раунд 6' => 18958,
    ];

    // Плей-офф (Путь регионов)
    private const REGIONS_PLAYOFF = [
        'Путь регионов. Плей-офф' => 18989,
    ];

    private const BASE_URL = 'https://www.rfs.ru/cup/tournament/matches';

    public function parse(string $url = ''): array
    {
        $data = [];

        // 1. Групповой этап — все сыгранные матчи
        foreach (self::GROUP_ROUNDS as $groupName => $roundId) {
            $matches = $this->fetchRound($roundId, $groupName, 'before');
            $data = array_merge($data, $matches);
        }

        // 2. Плей-офф — сыгранные матчи из сетки (двухматчевые пары)
        if ($url) {
            $bracketMatches = $this->parsePlayoffBracket($url);
            $data = array_merge($data, $bracketMatches);
        }

        // 3. Плей-офф РПЛ — предстоящие матчи
        foreach (self::PLAYOFF_ROUNDS as $groupName => $roundId) {
            $matches = $this->fetchRound($roundId, $groupName, 'after');
            $data = array_merge($data, $matches);
        }

        // 4. Путь регионов — сыгранные матчи (все раунды)
        foreach (self::REGIONS_ROUNDS as $groupName => $roundId) {
            $matches = $this->fetchRound($roundId, $groupName, 'before');
            $data = array_merge($data, $matches);
        }

        // 5. Путь регионов — плей-офф из сетки на странице турнира
        if ($url) {
            $regionsBracket = $this->parseRegionsPlayoffBracket($url);
            $data = array_merge($data, $regionsBracket);
        } else {
            // Fallback: только финал через API
            foreach (self::REGIONS_PLAYOFF as $groupName => $roundId) {
                $matches = $this->fetchRound($roundId, $groupName, '');
                $data = array_merge($data, $matches);
            }
        }

        return $data;
    }

    /**
     * Парсит плей-офф «Путь регионов» из сетки на странице турнира.
     * Блок: .bet-tournament-table.region, три колонки round-1/2/3.
     */
    private function parseRegionsPlayoffBracket(string $url): array
    {
        $crawler = $this->getCrawler($url);
        $data = [];

        $roundMap = [
            'round-1' => 'Путь регионов. 1/4 финала',
            'round-2' => 'Путь регионов. 1/2 финала',
            'round-3' => 'Путь регионов. Финал',
        ];

        $crawler->filter('.bet-tournament-table.region .playoff-col')->each(function ($col) use (&$data, $roundMap) {
            $classes = $col->attr('class') ?? '';
            $groupName = null;
            foreach ($roundMap as $cls => $name) {
                if (str_contains($classes, $cls)) {
                    $groupName = $name;
                    break;
                }
            }
            if (!$groupName) return;

            $col->filter('.playoff-match-cont')->each(function ($node) use (&$data, $groupName) {
                $team1 = $node->filter('.team1 .playoff-match-team-title span')->count()
                    ? trim($node->filter('.team1 .playoff-match-team-title span')->text())
                    : '';
                $team2 = $node->filter('.team2 .playoff-match-team-title span')->count()
                    ? trim($node->filter('.team2 .playoff-match-team-title span')->text())
                    : '';

                $team1Logo = $node->filter('.team1 .playoff-match-team-logo img')->count()
                    ? $node->filter('.team1 .playoff-match-team-logo img')->attr('src')
                    : null;
                $team2Logo = $node->filter('.team2 .playoff-match-team-logo img')->count()
                    ? $node->filter('.team2 .playoff-match-team-logo img')->attr('src')
                    : null;

                $scoreOrDate = null;
                $isPlayed = false;
                $penaltyWinner = null;

                $scoreLink = $node->filter('.playoff-match-score a');
                if ($scoreLink->count()) {
                    $scoreText = $scoreLink->filter('.score')->count()
                        ? trim($scoreLink->filter('.score')->text())
                        : '';
                    $dateText = $scoreLink->filter('.date-time')->count()
                        ? trim(preg_replace('/\s+/', ' ', $scoreLink->filter('.date-time')->text()))
                        : '';

                    if ($scoreText) {
                        $scoreOrDate = $scoreText;
                        $isPlayed = true;

                        // Пенальти: (2:4) в .additional
                        if ($scoreLink->filter('.additional')->count()) {
                            $additional = trim($scoreLink->filter('.additional')->text());
                            if (preg_match('/\((\d+):(\d+)\)/', $additional, $pm)) {
                                $penaltyWinner = ((int)$pm[1] > (int)$pm[2]) ? 'team1' : 'team2';
                                $scoreOrDate .= ' (' . $pm[1] . ':' . $pm[2] . ')';
                            }
                        }
                    } elseif ($dateText) {
                        // Дата уже в формате dd.mm.yyyy
                        if (preg_match('/(\d{2}\.\d{2}\.\d{4})/', $dateText, $dm)) {
                            $scoreOrDate = $dm[1];
                        } else {
                            $scoreOrDate = $dateText;
                        }
                        $isPlayed = false;
                    }
                }

                if (($team1 || $team2) && $scoreOrDate !== null) {
                    $data[] = [
                        'group_name'     => $groupName,
                        'team1'          => $team1,
                        'team1_logo'     => $team1Logo,
                        'team1_city'     => null,
                        'team2'          => $team2,
                        'team2_logo'     => $team2Logo,
                        'team2_city'     => null,
                        'score_or_date'  => $scoreOrDate,
                        'is_played'      => $isPlayed,
                        'penalty_winner' => $penaltyWinner,
                    ];
                }
            });
        });

        return $data;
    }

    /**
     * Старый парсер плей-офф сетки (/cup/tournament).
     * Парсит двухматчевые пары со счётами вида "3:1 | 3:2".
     */
    private function parsePlayoffBracket(string $url): array
    {
        $crawler = $this->getCrawler($url);
        $data = [];

        $crawler->filter('.bet-tournament-table .playoff-match-cont')->each(function ($node) use (&$data) {
            $team1 = $node->filter('.team1 .playoff-match-team-title span')->count()
                ? $node->filter('.team1 .playoff-match-team-title span')->text()
                : '';
            $team2 = $node->filter('.team2 .playoff-match-team-title span')->count()
                ? $node->filter('.team2 .playoff-match-team-title span')->text()
                : '';

            $scoresAndDates = $node->filter('.playoff-match-score a')->each(function ($scoreNode) {
                $score = $scoreNode->filter('.score')->count()
                    ? trim($scoreNode->filter('.score')->text())
                    : '';
                $date = $scoreNode->filter('.date-time')->count()
                    ? trim(preg_replace('/\s+/', ' ', $scoreNode->filter('.date-time')->text()))
                    : '';
                return $score ?: $date;
            });

            $scoresAndDates = array_filter($scoresAndDates);

            if ($team1 || $team2) {
                $entry = [
                    'group_name'     => 'Путь РПЛ. Плей-офф',
                    'team1'          => trim($team1),
                    'team1_logo'     => null,
                    'team2'          => trim($team2),
                    'team2_logo'     => null,
                    'score_or_date'  => implode(' | ', $scoresAndDates),
                    'is_played'      => true,
                    'penalty_winner' => null,
                ];
                if (!in_array($entry, $data)) {
                    $data[] = $entry;
                }
            }
        });

        return $data;
    }

    private function fetchRound(int $roundId, string $groupName, string $dateFilter = ''): array
    {
        $baseUrl = self::BASE_URL . '?TournamentMatchesFilter%5BroundId%5D=' . $roundId;
        if ($dateFilter) {
            $baseUrl .= '&TournamentMatchesFilter%5Bdate%5D=' . $dateFilter;
        }

        $data = [];
        $page = 1;

        do {
            $url = $baseUrl . ($page > 1 ? '&page=' . $page : '');
            $crawler = $this->getCrawler($url);

            $items = $crawler->filter('.bet-tournament-region__item');
            if ($items->count() === 0) {
                break;
            }

            $items->each(function ($item) use (&$data, $groupName) {
                $date = $item->filter('.bet-tournament__date')->count()
                    ? trim($item->filter('.bet-tournament__date')->text())
                    : '';

                $team1Node = $item->filter('.tour-match__team.first');
                $team2Node = $item->filter('.tour-match__team.last');

                if (!$team1Node->count() || !$team2Node->count()) {
                    return;
                }

                $team1 = trim($team1Node->filter('.tour-match__name')->text());
                $team2 = trim($team2Node->filter('.tour-match__name')->text());

                $team1Logo = $team1Node->filter('img')->count()
                    ? $team1Node->filter('img')->attr('src')
                    : null;
                $team2Logo = $team2Node->filter('img')->count()
                    ? $team2Node->filter('img')->attr('src')
                    : null;

                $team1City = $team1Node->filter('.tour-match__city')->count()
                    ? trim($team1Node->filter('.tour-match__city')->text())
                    : null;
                $team2City = $team2Node->filter('.tour-match__city')->count()
                    ? trim($team2Node->filter('.tour-match__city')->text())
                    : null;

                $scoreBlock = $item->filter('.tour-match__score');
                $isPlayed = false;
                $scoreOrDate = null;
                $penaltyWinner = null;

                if ($scoreBlock->count()) {
                    $classes = $scoreBlock->attr('class') ?? '';

                    if (str_contains($classes, 'time')) {
                        // Предстоящий матч — есть время
                        $time = trim($scoreBlock->text());
                        $scoreOrDate = $this->formatDate($date) . ' ' . $time;
                        $isPlayed = false;
                    } else {
                        $spans = $scoreBlock->filter('span')->each(fn($s) => trim($s->text()));
                        $spans = array_filter($spans, fn($s) => $s !== '&nbsp;' && $s !== ':' && $s !== '');
                        $spanValues = array_values($spans);

                        if (in_array('-', $spanValues)) {
                            // Дата ещё не назначена
                            $scoreOrDate = $this->formatDate($date);
                            $isPlayed = false;
                        } else {
                            $nums = array_values(array_filter($spanValues, fn($s) => is_numeric($s)));

                            if (count($nums) >= 2) {
                                $g1 = (int)$nums[0];
                                $g2 = (int)$nums[1];
                                $scoreOrDate = $g1 . ':' . $g2;
                                $isPlayed = true;

                                // Пенальти — "б" в спанах
                                $hasPenalty = in_array('б', $spanValues, true);
                                if ($hasPenalty && $g1 === $g2) {
                                    if (count($nums) >= 4) {
                                        // Счёт серии пенальти доступен: nums[2]:nums[3]
                                        $penaltyWinner = ((int)$nums[2] > (int)$nums[3]) ? 'team1' : 'team2';
                                    } else {
                                        // Пытаемся определить по классу winning
                                        $t1Classes = $team1Node->attr('class') ?? '';
                                        $t2Classes = $team2Node->attr('class') ?? '';
                                        if (preg_match('/\b(win|winner|is-win|is-winner)\b/', $t1Classes)) {
                                            $penaltyWinner = 'team1';
                                        } elseif (preg_match('/\b(win|winner|is-win|is-winner)\b/', $t2Classes)) {
                                            $penaltyWinner = 'team2';
                                        }
                                    }
                                }
                            }
                        }
                    }
                }

                if ($team1 && $team2 && $scoreOrDate !== null) {
                    $data[] = [
                        'group_name'     => $groupName,
                        'team1'          => $team1,
                        'team1_logo'     => $team1Logo,
                        'team1_city'     => $team1City,
                        'team2'          => $team2,
                        'team2_logo'     => $team2Logo,
                        'team2_city'     => $team2City,
                        'score_or_date'  => $scoreOrDate,
                        'is_played'      => $isPlayed,
                        'penalty_winner' => $penaltyWinner,
                    ];
                }
            });

            // Проверяем наличие следующей страницы
            $hasNextPage = $crawler->filter('.pagination li.next:not(.disabled) a')->count() > 0
                        || $crawler->filter('a[rel="next"]')->count() > 0;

            $page++;
        } while ($hasNextPage && $page <= 20);

        return $data;
    }

    /**
     * Конвертирует "30 июля 2025" → "30.07.2025"
     */
    private function formatDate(string $date): string
    {
        $months = [
            'января' => '01', 'февраля' => '02', 'марта' => '03',
            'апреля' => '04', 'мая' => '05', 'июня' => '06',
            'июля' => '07', 'августа' => '08', 'сентября' => '09',
            'октября' => '10', 'ноября' => '11', 'декабря' => '12',
        ];

        // Обрабатываем диапазон типа "5 - 7 мая 2026" → берём первую дату
        $date = preg_replace('/^(\d+)\s*-\s*\d+/', '$1', $date);

        if (preg_match('/(\d+)\s+(\S+)\s+(\d{4})/', $date, $m)) {
            $month = $months[$m[2]] ?? '01';
            return sprintf('%02d.%s.%s', (int) $m[1], $month, $m[3]);
        }

        return $date;
    }
}
