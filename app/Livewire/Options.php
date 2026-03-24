<?php

namespace App\Livewire;

use App\Models\Option;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\View;
use Livewire\Component;

class Options extends Component
{
    public Collection $data;
    public bool $isModalOpen = false;
    public int $optionId;
    public string $code;
    public string $title;
    public string $value;
    public string $type;
    public bool $active;
    public bool $enabled;

    public function render(): View
    {
        $this->data = Option::query()->orderBy('sort')->get();
        return view('livewire.options');
    }

    public function create()
    {
        $this->resetInputFields();
        $this->openModal();
    }

    public function clearCache()
    {
        \Cache::store('file')->flush();
        \Cache::flush();
    }

    public function openModal()
    {
        $this->isModalOpen = true;
    }

    public function closeModal()
    {
        $this->isModalOpen = false;
    }

    private function resetInputFields()
    {
        $this->optionId = 0;
        $this->code = '';
        $this->title = '';
        $this->value = '';
        $this->type = '';
        $this->active = false;
        $this->enabled = false;
    }

    public function store()
    {
        $validatedData = $this->validate([
            'code' => 'required|unique:options,code,' . $this->optionId,
            'title' => 'required|string',
            'value' => 'nullable|string',
            'active' => '',
            'enabled' => '',
        ]);

        Option::updateOrCreate(['id' => $this->optionId], $validatedData);

        $this->closeModal();
        $this->resetInputFields();
        $this->clearCache();
    }

    public function edit(int $id)
    {
        /** @var Option $option */
        $option = Option::findOrFail($id);
        $this->optionId = $option->id ?? '';
        $this->code = $option->code ?? '';
        $this->title = $option->title ?? '';
        $this->value = $option->value ?? '';
        $this->type = $option->type ?? '';
        $this->active = (bool)$option->active;
        $this->enabled = (bool)$option->enabled;

        $this->openModal();
    }

    public function delete($id)
    {
        Option::find($id)->delete();
        $this->closeModal();
        $this->resetInputFields();
    }
}
