<?php

namespace App\Livewire;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\View\View;
use Livewire\Component;

abstract class ModelEditor extends Component
{
    public bool $showModal = false;
    public ?Model $model = null;

    public array $formData;
    public array $formDataDefaults;

    public function mount(): void
    {
        $this->resetForm();
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

        if ($this->model) {
            $this->model->update($this->formData);
        } else {
            $this->model = $this->createModel();
            $this->model->fill($this->formData);
            $this->model->save();
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

    public function render(): View {}

    protected function createModel(): Model {}
}
