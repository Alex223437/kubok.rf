<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\Parsers\KhlParser;
use App\Services\Parsers\RfsCupParser;
use App\Services\Parsers\BasketballParser;
use App\Models\KhlStanding;
use App\Models\RfsMatch;
use App\Models\UpcomingMatch;
use App\Models\BasketballStanding;
use App\Models\BasketballPlayoffPair;

class ParseLeaguesCommand extends Command
{
    protected $signature = 'app:parse-leagues {--league= : Specify the league to parse (khl, rfs, basket)}';
    protected $description = 'Parse sports leagues tables and save to database';

    public function handle(KhlParser $khlParser, RfsCupParser $rfsCupParser, BasketballParser $basketballParser)
    {
        $league = $this->option('league');

        $this->info('Starting parser...');

        if (!$league || $league === 'khl') {
            $this->info('Parsing KHL standings...');
            try {
                $khlData = $khlParser->parse('https://www.khl.ru/standings/');
                if (!empty($khlData)) {
                    KhlStanding::truncate();
                    foreach ($khlData as $row) {
                        KhlStanding::create([
                            'rank'       => $row['Место'] ?? null,
                            'team'       => $row['Клуб'] ?? '',
                            'logo'       => $row['Логотип'] ?? null,
                            'conference' => $row['Конференция'] ?? null,
                            'division'   => $row['Дивизион'] ?? null,
                            'games'      => $row['И'] ?? null,
                            'wins'       => $row['В'] ?? null,
                            'ot_wins'    => $row['ВО'] ?? null,
                            'so_wins'    => $row['ВБ'] ?? null,
                            'so_losses'  => $row['ПБ'] ?? null,
                            'ot_losses'  => $row['ПО'] ?? null,
                            'pp'         => $row['ПП'] ?? null,
                            'losses'     => $row['П'] ?? null,
                            'goals'      => $row['Ш'] ?? null,
                            'points'     => $row['О'] ?? null,
                        ]);
                    }
                    $this->table(array_keys($khlData[0]), $khlData);
                } else {
                    $this->warn('Playoff mode detected — standings preserved. Updating logos and divisions.');
                    $logos = $khlParser->fetchTeamLogos();
                    foreach (KhlStanding::all() as $standing) {
                        $updates = [];
                        if (!empty($logos)) {
                            $logo = $logos[$standing->team]
                                ?? $logos[$khlParser->normalizeTeamName($standing->team)]
                                ?? null;
                            if ($logo) $updates['logo'] = $logo;
                        }
                        $divInfo = $khlParser->getDivisionInfo($standing->team);
                        if ($divInfo) {
                            $updates['conference'] = $divInfo['conference'];
                            $updates['division']   = $divInfo['division'];
                        }
                        if (!empty($updates)) $standing->update($updates);
                    }
                    $this->info('Updated ' . KhlStanding::whereNotNull('conference')->count() . ' teams with conference/division.');
                }
                // Ближайшие матчи КХЛ
                $this->info('Fetching upcoming KHL matches...');
                UpcomingMatch::where('sport', 'khl')->delete();
                $upcomingKhl = $khlParser->fetchUpcomingMatches();
                foreach ($upcomingKhl as $match) {
                    UpcomingMatch::create([
                        'sport'       => 'khl',
                        'league_name' => $match['league_name'],
                        'team1'       => $match['team1'],
                        'team1_logo'  => $match['team1_logo'],
                        'team2'       => $match['team2'],
                        'team2_logo'  => $match['team2_logo'],
                        'match_at'    => $match['match_at'],
                    ]);
                }
                $this->info('Upcoming KHL matches synced: ' . count($upcomingKhl));

                $this->info('KHL parsed successfully.');
            } catch (\Exception $e) {
                $this->error('KHL parsing error: ' . $e->getMessage() . " on line " . $e->getLine());
            }
        }

        if (!$league || $league === 'rfs') {
            $this->info('Parsing RFS Cup standings...');
            try {
                $rfsData = $rfsCupParser->parse('https://www.rfs.ru/cup/tournament');
                if (!empty($rfsData)) {
                    RfsMatch::truncate();
                    foreach ($rfsData as $row) {
                        RfsMatch::create([
                            'group_name'     => $row['group_name'] ?? null,
                            'team1'          => $row['team1'] ?? '',
                            'team1_logo'     => $row['team1_logo'] ?? null,
                            'team1_city'     => $row['team1_city'] ?? null,
                            'team2'          => $row['team2'] ?? '',
                            'team2_logo'     => $row['team2_logo'] ?? null,
                            'team2_city'     => $row['team2_city'] ?? null,
                            'score_or_date'  => $row['score_or_date'] ?? null,
                            'is_played'      => $row['is_played'] ?? true,
                            'penalty_winner' => $row['penalty_winner'] ?? null,
                        ]);
                    }
                    $this->table(array_keys($rfsData[0]), $rfsData);
                } else {
                    $this->warn('No RFS data parsed.');
                }
                // Синхронизируем предстоящие матчи в универсальную таблицу
                UpcomingMatch::where('sport', 'rfs')->delete();
                $teamLogos = [];
                foreach (RfsMatch::all() as $_m) {
                    if ($_m->team1_logo && !isset($teamLogos[$_m->team1])) $teamLogos[$_m->team1] = $_m->team1_logo;
                    if ($_m->team2_logo && !isset($teamLogos[$_m->team2])) $teamLogos[$_m->team2] = $_m->team2_logo;
                }
                foreach (RfsMatch::all() as $rm) {
                    if (!preg_match('/^(\d{2})\.(\d{2})\.(\d{4})(?:\s+(\d{2}:\d{2}))?$/', $rm->score_or_date, $m)) continue;
                    if (empty($rm->team1) || empty($rm->team2)) continue;
                    $time = isset($m[4]) ? $m[4] : '00:00';
                    UpcomingMatch::create([
                        'sport'       => 'rfs',
                        'league_name' => $rm->group_name ?? 'Кубок России',
                        'team1'       => $rm->team1,
                        'team1_logo'  => $rm->team1_logo ?: ($teamLogos[$rm->team1] ?? null),
                        'team1_city'  => $rm->team1_city,
                        'team2'       => $rm->team2,
                        'team2_logo'  => $rm->team2_logo ?: ($teamLogos[$rm->team2] ?? null),
                        'team2_city'  => $rm->team2_city,
                        'match_at'    => $m[3] . '-' . $m[2] . '-' . $m[1] . ' ' . $time . ':00',
                    ]);
                }
                $this->info('Upcoming RFS matches synced: ' . UpcomingMatch::where('sport', 'rfs')->count());

                $this->info('RFS Cup parsed successfully.');
            } catch (\Exception $e) {
                $this->error('RFS Cup parsing error: ' . $e->getMessage() . " on line " . $e->getLine());
            }
        }

        if (!$league || $league === 'basket') {
            $this->info('Parsing Basketball standings...');
            $tags = ['msl', 'mhl', 'wpremier'];

            BasketballStanding::truncate();
            BasketballPlayoffPair::truncate();

            foreach ($tags as $tag) {
                try {
                    $this->info("Fetching standings for tag: {$tag}");
                    $basketData = $basketballParser->parse($tag);

                    if (!empty($basketData)) {
                        foreach ($basketData as $row) {
                            BasketballStanding::create([
                                'tag'        => $tag,
                                'section'    => $row['section'] ?? null,
                                'rank'       => $row['Место'] ?? null,
                                'team'       => $row['Команда'] ?? '',
                                'logo'       => $row['Логотип'] ?? null,
                                'region_name'=> $row['Регион'] ?? null,
                                'games'      => $row['И'] ?? null,
                                'wins'       => $row['В'] ?? null,
                                'win_pct'    => $row['%'] ?? null,
                                'losses'     => $row['П'] ?? null,
                                'points'     => $row['О'] ?? null,
                                'plus_minus' => ($row['+'] ?? '0') . '/' . ($row['-'] ?? '0'),
                                'diff'       => $row['Разница'] ?? null,
                                'last_5'     => $row['Последние 5'] ?? null,
                            ]);
                        }
                        $this->info("Standings saved for {$tag}: " . count($basketData) . " teams");
                    } else {
                        $this->warn("No standings data for {$tag}");
                    }

                    // Плей-офф пары + ближайшие матчи
                    $this->info("Fetching playoff pairs for tag: {$tag}");
                    $json = $basketballParser->fetchJsonPublic($tag);
                    $playoffData = $basketballParser->parsePlayoffPairs($json);

                    if (!empty($playoffData)) {
                        foreach ($playoffData as $row) {
                            BasketballPlayoffPair::create(array_merge($row, ['tag' => $tag]));
                        }
                        $this->info("Playoff pairs saved for {$tag}: " . count($playoffData) . " pairs");
                    } else {
                        $this->warn("No playoff data for {$tag}");
                    }

                    // Синхронизируем ближайшие матчи баскетбола
                    UpcomingMatch::where('sport', 'basketball')->where('league_name', 'LIKE', '%' . $tag . '%')->delete();
                    $upcomingCount = 0;
                    foreach ($playoffData as $row) {
                        foreach ($row['games'] ?? [] as $game) {
                            if (($game['status'] ?? '') !== 'Scheduled' || empty($game['date'])) continue;
                            // date = "01.04" → добавляем год
                            if (preg_match('/^(\d{2})\.(\d{2})$/', $game['date'], $dm)) {
                                $matchAt = '2026-' . $dm[2] . '-' . $dm[1] . ' 00:00:00';
                            } else {
                                continue;
                            }
                            if (empty($row['team1_name']) || empty($row['team2_name'])) continue;
                            // Пропускаем технические TBD-записи
                            if (str_contains($row['team1_name'] ?? '', 'Победитель') ||
                                str_contains($row['team1_name'] ?? '', 'Проигравший')) continue;

                            UpcomingMatch::create([
                                'sport'       => 'basketball',
                                'league_name' => $row['section_name'] . ' (' . strtoupper($tag) . ')',
                                'team1'       => $row['team1_name'],
                                'team1_logo'  => $row['team1_logo'],
                                'team1_city'  => $row['team1_region'],
                                'team2'       => $row['team2_name'],
                                'team2_logo'  => $row['team2_logo'],
                                'team2_city'  => $row['team2_region'],
                                'match_at'    => $matchAt,
                            ]);
                            $upcomingCount++;
                        }
                    }
                    if ($upcomingCount > 0) {
                        $this->info("Upcoming basketball matches synced for {$tag}: {$upcomingCount}");
                    }
                } catch (\Exception $e) {
                    $this->error("Basketball parsing error for {$tag}: " . $e->getMessage() . " on line " . $e->getLine());
                }
            }
        }

        $this->info('Parsing complete.');
    }
}
