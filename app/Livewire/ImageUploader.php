<?php

namespace App\Livewire;

use Illuminate\Database\Eloquent\Model;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Livewire\WithFileUploads;
use App\Models\FilePath;
use Illuminate\Support\Facades\Storage;

class ImageUploader extends Component
{
    use WithFileUploads;

    public ?TemporaryUploadedFile $image = null;
    public Model $model;
    public ?FilePath $filePath;
    public string $field;

    public function mount(Model $model, string $field)
    {
        $this->model = $model;
        $this->field = $field;
        $this->filePath = FilePath::find($model->$field) ?? new FilePath();
    }

    public function updatedImage()
    {
        $this->validate([
            'image' => 'image|max:2048',
        ]);

        $modelName = strtolower(class_basename($this->model));

        $storagePath = $this->image->store("img/{$modelName}/$this->field", 'public');
        //$hash = md5_file($this->image->getRealPath());
        $path = Storage::url($storagePath);

        //$this->filePath->delete();
        $this->filePath->fill([
            'path' => $path,
            'name' => $this->image->getClientOriginalName(),
            'ext' => $this->image->getClientOriginalExtension(),
            'size' => $this->image->getSize(),
        ]);
        $this->filePath->save();

        $this->model->update([$this->field => $this->filePath->id]);

        $this->dispatch('image-uploaded-' . $this->field);
    }

    public function render()
    {
        return view('livewire.image-uploader');
    }
}
