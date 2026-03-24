<?php

namespace App\Livewire;

use App\Models\PageCharity;

class PageCharitiesManager extends PageRelationsManager
{
    public string $relation = 'charities';
    public string $template = 'livewire.page-charities-manager';

    public array $formDataDefaults = [
        'sort' => 0,
        'type' => '',
        'title' => '',
        'text' => '',
        'html' => '',
        'active' => true,
        'url' => '',
        'payload' => '',
    ];

    protected function findModelById(int $id): PageCharity
    {
        return PageCharity::find($id);
    }
}
