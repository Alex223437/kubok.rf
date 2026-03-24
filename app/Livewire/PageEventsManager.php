<?php

namespace App\Livewire;

use App\Models\PageEvent;

class PageEventsManager extends PageRelationsManager
{
    public string $relation = 'events';
    public string $template = 'livewire.page-events-manager';

    public array $formDataDefaults = [
        'sort' => 0,
        'type' => '',
        'title' => '',
        'text' => '',
        'team1' => '',
        'team2' => '',
        'active' => true,
        'date_start' => null,
        'date_end' => null,
        'url' => '',
        'payload' => ['live' => false],
    ];

    protected function onBeforeSave(): void
    {
        $this->formData['date_start'] = $this->formData['date_start'] ?: null;
        $this->formData['date_end'] = $this->formData['date_end'] ?: null;
    }

    protected function findModelById(int $id): PageEvent
    {
        $model = PageEvent::find($id);
        if (!is_array($model->payload)) {
            $model->payload = $this->formDataDefaults['payload'];
        }
        return $model;
    }
}
