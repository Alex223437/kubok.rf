<?php

namespace App\Livewire;

use App\Models\PageInfo;

class PageInfosManager extends PageRelationsManager
{
    public string $relation = 'infos';
    public string $template = 'livewire.page-infos-manager';

    public array $formDataDefaults = [
        'sort' => 0,
        'type' => '',
        'title' => '',
        'text' => '',
        'html' => '',
        'banner_id' => null,
        'active' => true,
    ];

    protected function findModelById(int $id): PageInfo
    {
        return PageInfo::find($id);
    }

    protected function onBeforeSave(): void
    {
        if (empty($this->formData['banner_id'])) {
            $this->formData['banner_id'] = null;
        }
    }
}
