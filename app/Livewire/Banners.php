<?php

namespace App\Livewire;

use App\Models\Banner;
use Illuminate\Database\Eloquent\Model;
use Illuminate\View\View;

class Banners extends ModelEditor
{
    public bool $showModal = false;
    public ?Model $model = null;

    public array $formData = [];
    public array $formDataDefaults = [
        'title' => '',
        'code' => '',
        'url' => '',
        'html_xs' => '',
        'html_md' => '',
        'html_xl' => '',
        'active' => true,
    ];

    protected function findModelById(int $id): Banner
    {
        return Banner::find($id);
    }

    protected function createModel(): Banner
    {
        return new Banner();
    }

    public function render(): View
    {
        $items = Banner::all();
        return view('livewire.banners', ['items' => $items]);
    }
}
