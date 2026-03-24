<?php

namespace App\Livewire;

use App\Models\Page;
use Livewire\Component;

class Pages extends Component
{
    public function render()
    {
        $items = Page::all();
        return view('livewire.pages', ['items' => $items]);
    }
}
