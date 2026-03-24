<?php

namespace App\Livewire;

use App\Models\PageTable;

class PageTablesManager extends PageRelationsManager
{
    public string $relation = 'tables';
    public string $template = 'livewire.page-tables-manager';

    public array $formDataDefaults = [
        'sort' => 0,
        'type' => '',
        'title' => '',
        'short' => '',
        'active' => true,
        'payload' => [
            'headers' => [],
            'values' => [],
        ],
    ];

    protected function findModelById(int $id): PageTable
    {
        return PageTable::find($id);
    }

    public function addHeader()
    {
        if (!isset($this->formData['payload']['headers'])) {
            $this->formData['payload']['headers'] = [];
        }
        $this->formData['payload']['headers'][] = ['title' => '', 'hint' => ''];
    }

    public function removeHeader($index)
    {
        unset($this->formData['payload']['headers'][$index]);
        $this->formData['payload']['headers'] = array_values($this->formData['payload']['headers']);
    }

    public function addRow()
    {
        if (!isset($this->formData['payload']['values'])) {
            $this->formData['payload']['values'] = [];
        }
        $headerCount = count($this->formData['payload']['headers'] ?? []);
        $this->formData['payload']['values'][] = array_fill(0, $headerCount, '');
    }

    public function removeRow($index)
    {
        unset($this->formData['payload']['values'][$index]);
        $this->formData['payload']['values'] = array_values($this->formData['payload']['values']);
    }

    /**
     * Вырезаем перемещаемую строку из массива
     * Вставляем её на новую позицию
     * Обновляем массив данных
     */
    public function reorderRows($fromIndex, $toIndex)
    {
        //dd($fromIndex, $toIndex);
        $rows = $this->formData['payload']['values'];
        $moving = array_splice($rows, (int)$fromIndex, 1)[0];
        array_splice($rows, (int)$toIndex, 0, [$moving]);
        $this->formData['payload']['values'] = $rows;
    }

}
