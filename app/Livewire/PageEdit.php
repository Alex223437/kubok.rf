<?php

namespace App\Livewire;

use App\Models\Page;
use Illuminate\Http\Request;
use Livewire\Component;

class PageEdit extends Component
{
    public Page $page;

    public $title = '';
    public $description = '';
    public $meta_title = '';
    public $meta_description = '';
    public $meta_keywords = '';
    public $code = '';
    public $sort = 0;
    public $active = true;
    public $type = '';
    public $html = '';
    public $facts = '';
    public $payload = [];
    public $banner_id = null;

    public function render(Request $request)
    {
        return view('livewire.page', ['page' => $this->page]);
    }


    public function mount($id)
    {
        $page = Page::findOrFail($id);
        $this->page = $page;
        $this->title = $page->title;
        $this->description = $page->description;
        $this->meta_title = $page->meta_title;
        $this->meta_description = $page->meta_description;
        $this->meta_keywords = $page->meta_keywords;
        $this->code = $page->code;
        $this->sort = $page->sort;
        $this->active = $page->active;
        $this->type = $page->type;
        $this->html = $page->html;
        $this->facts = $page->facts;
        $this->payload = is_array($page->payload) ? $page->payload : [];
        $this->banner_id = $page->banner_id;
    }

    public function save()
    {
        $this->validate();

        $this->page->fill([
            'title' => $this->title,
            'description' => $this->description,
            'code' => $this->code,
            'sort' => $this->sort,
            'active' => $this->active,
            'type' => $this->type,
            'html' => $this->html,
            'facts' => $this->facts,
            'payload' => $this->payload,
            'banner_id' => $this->banner_id ?: null,
        ]);
        $this->page->save();

        session()->flash('message', 'ok');
    }

    public function saveSeo()
    {
        $this->validate();

        $this->page->fill([
            'meta_title' => $this->meta_title,
            'meta_description' => $this->meta_description,
            'meta_keywords' => $this->meta_keywords,
        ]);
        $this->page->save();

        session()->flash('message', 'ok');
    }

    public function savePayload()
    {

        $this->page->payload = $this->payload;
        $this->page->save();

        session()->flash('message', 'ok');
    }

    protected function rules()
    {
        return [
            'title' => ['required'],
            'code' => ['required'],
        ];
    }
}
