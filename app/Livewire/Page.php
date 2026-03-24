<?php

namespace App\Livewire;

use App\Models;
use Livewire\Component;

class Page extends Component
{
    public function render()
    {
        $items = Page::all();
        return view('livewire.pages', ['items' => $items]);
    }
}
