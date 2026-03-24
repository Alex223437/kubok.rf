<?php

namespace App\Livewire;

use App\Models\PageMoment;

class PageMomentsManager extends PageRelationsManager
{
    public string $relation = 'moments';
    public string $template = 'livewire.page-moments-manager';

    public array $formDataDefaults = [
        'sort' => 0,
        'type' => '',
        'title' => '',
        'text' => '',
        'html' => '',
        'active' => true,
    ];

    protected function findModelById(int $id): PageMoment
    {
        return PageMoment::find($id);
    }
}
