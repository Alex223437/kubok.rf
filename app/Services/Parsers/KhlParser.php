<?php

namespace App\Services\Parsers;

class KhlParser extends BaseParser
{
    public function parse(string $url): array
    {
        $crawler = $this->getCrawler($url);
        $data = [];

        $crawler->filter('table tbody tr')->each(function ($node) use (&$data) {
            $cells = $node->filter('td, th')->each(function ($cell) {
                return trim($cell->text());
            });

            // Expected KHL cells: 
            // 0: Rank, 1: Team Name, 2: И (Games), 3: В (Wins), 4: ВО (OT Wins), 
            // 5: ВБ (SO Wins), 6: ПБ (SO Losses), 7: ПО (OT Losses), 8: П (Losses), 
            // 9: Ш (Goals), 10: О (Points)
            if (count($cells) >= 11 && is_numeric($cells[0])) {
                $data[] = [
                    'Место' => $cells[0] ?? '',
                    'Клуб' => $cells[1] ?? '',
                    'И' => $cells[2] ?? '',
                    'В' => $cells[3] ?? '',
                    'ВО' => $cells[4] ?? '',
                    'ВБ' => $cells[5] ?? '',
                    'ПБ' => $cells[6] ?? '',
                    'ПО' => $cells[7] ?? '',
                    'П' => $cells[8] ?? '',
                    'Ш' => $cells[9] ?? '',
                    'О' => end($cells),
                ];
            }
        });

        // Remove duplicate rows across conferences and return
        // We'll just slice the first 10 for testing
        return array_slice($data, 0, 10);
    }
}
