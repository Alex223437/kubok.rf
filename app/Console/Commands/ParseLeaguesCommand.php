<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\Parsers\KhlParser;
use App\Services\Parsers\RfsCupParser;
use App\Services\Parsers\BasketballParser;
use App\Models\KhlStanding;
use App\Models\RfsMatch;
use App\Models\BasketballStanding;

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
                            'rank'      => $row['Место'] ?? null,
                            'team'      => $row['Клуб'] ?? '',
                            'logo'      => $row['Логотип'] ?? null,
                            'games'     => $row['И'] ?? null,
                            'wins'      => $row['В'] ?? null,
                            'ot_wins'   => $row['ВО'] ?? null,
                            'so_wins'   => $row['ВБ'] ?? null,
                            'so_losses' => $row['ПБ'] ?? null,
                            'ot_losses' => $row['ПО'] ?? null,
                            'pp'        => $row['ПП'] ?? null,
                            'losses'    => $row['П'] ?? null,
                            'goals'     => $row['Ш'] ?? null,
                            'points'    => $row['О'] ?? null,
                        ]);
                    }
                    $this->table(array_keys($khlData[0]), $khlData);
                } else {
                    $this->warn('Playoff mode detected — standings preserved. Updating logos only.');
                    $logos = $khlParser->fetchTeamLogos();
                    if (!empty($logos)) {
                        foreach (KhlStanding::all() as $standing) {
                            $logo = $logos[$standing->team]
                                ?? $logos[$khlParser->normalizeTeamName($standing->team)]
                                ?? null;
                            if ($logo) {
                                $standing->update(['logo' => $logo]);
                            }
                        }
                        $this->info('Logos updated for ' . KhlStanding::whereNotNull('logo')->count() . ' teams.');
                    }
                }
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
                $this->info('RFS Cup parsed successfully.');
            } catch (\Exception $e) {
                $this->error('RFS Cup parsing error: ' . $e->getMessage() . " on line " . $e->getLine());
            }
        }

        if (!$league || $league === 'basket') {
            $this->info('Parsing Basketball standings...');
            $tags = ['msl', 'wsl', 'mhl', 'whl', 'wpremier'];

            try {
                BasketballStanding::truncate(); // Clear old data for all tags

                foreach ($tags as $tag) {
                    $this->info("Fetching data for tag: {$tag}");
                    $basketData = $basketballParser->parse($tag);

                    if (!empty($basketData)) {
                        foreach ($basketData as $row) {
                            BasketballStanding::create([
                                'tag' => $tag, // Store the league tag
                                'rank' => $row['Место'] ?? null,
                                'team' => $row['Команда'] ?? '',
                                'games' => $row['И'] ?? null,
                                'wins' => $row['В'] ?? null,
                                'losses' => $row['П'] ?? null,
                                'points' => $row['О'] ?? null,
                                'plus_minus' => $row['+/-'] ?? null,
                                'diff' => $row['Разница'] ?? null,
                                'last_5' => $row['Последние 5'] ?? null,
                            ]);
                        }
                        $this->info("Successfully parsed and saved {$tag}");
                    } else {
                        $this->warn("No data parsed for {$tag}");
                    }
                }
            } catch (\Exception $e) {
                $this->error('Basketball parsing error: ' . $e->getMessage() . " on line " . $e->getLine());
            }
        }

        $this->info('Parsing complete.');
    }
}
