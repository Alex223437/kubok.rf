<?php

namespace App\Livewire;

use App\Models\Banner;
use App\Models\Page;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\View\View;
use Livewire\Component;

abstract class BannersEdit extends Component
{
    public bool $showModal = false;
    public ?Model $model = null;
    public string $template = 'livewire.banners';

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

    public function mount(): void
    {
        $this->resetForm();
    }

    protected function findModelById(int $id): Banner
    {
        return Banner::find($id);
    }

    public function openModal($id = null): void
    {
        if ($id) {
            $model = $this->findModelById((int)$id);
            $this->model = $model;
            foreach ($this->formData as $k => $v) {
                $attr = $model->getAttribute($k);
                if ($attr instanceof Carbon) {
                    $this->formData[$k] = $attr->toDateTimeLocalString();
                } else {
                    $this->formData[$k] = $attr;
                }
            }
        } else {
            $this->resetForm();
            $this->model = null;
        }
        $this->showModal = true;
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->resetForm();
    }

    protected function rules(): array
    {
        return [
            'formData.title' => 'required|min:3',
        ];
    }

    public function save(): void
    {
        $this->validate();

        if ($this->model) {
            $this->model->update($this->formData);
        } else {
            $this->page->{$this->relation}()->create($this->formData);
        }

        $this->closeModal();
    }

    public function delete(): void
    {
        if ($this->model) {
            $this->model->delete();
            $this->showModal = false;
            $this->resetForm();
            $this->model = null;
        }
    }

    private function resetForm(): void
    {
        $this->formData = $this->formDataDefaults;
    }

    public function render(): View
    {
        $items = Banner::all();
        return view($this->template, ['items' => $items]);
    }
}
