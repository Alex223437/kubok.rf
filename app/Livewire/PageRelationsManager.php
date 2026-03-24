<?php

namespace App\Livewire;

use App\Models\Page;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\View\View;
use Livewire\Component;

abstract class PageRelationsManager extends Component
{
    public Page $page;
    public bool $showModal = false;
    public ?Model $model = null;
    public string $relation;
    public string $template;

    public array $formData;
    public array $formDataDefaults;

    public function mount($page): void
    {
        $this->resetForm();
        $this->page = $page;
    }

    protected function findModelById(int $id): Model {}

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

        $this->onBeforeSave();

        if ($this->model) {
            $this->model->update($this->formData);
        } else {
            $this->page->{$this->relation}()->create($this->formData);
        }

        $this->closeModal();
        $this->clearCache();
    }

    protected function onBeforeSave(): void {}

    public function clearCache()
    {
        \Cache::store('file')->forget($this->page->getTable());
        \Cache::store('file')->forget($this->page->getTable() . '_' . $this->page->id);
    }

    public function delete(): void
    {
        if ($this->model) {
            $this->model->delete();
            $this->showModal = false;
            $this->resetForm();
            $this->model = null;
            $this->clearCache();
        }
    }

    private function resetForm(): void
    {
        $this->formData = $this->formDataDefaults;
    }

    public function render(): View
    {
        return view($this->template);
    }
}
