<?php

namespace Tests\Feature\Models;

use App\Models\FilePath;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class FilePathTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    public function test_create_of_find_returns_existing_file_with_same_hash()
    {
        // Создаем первый файл
        $file1 = UploadedFile::fake()->image('photo1.jpg');
        $path1 = Storage::disk('public')->putFile('images', $file1);
        $fullPath1 = '/storage/' . $path1;

        $filePath1 = FilePath::createOfFind([
            'path' => $fullPath1,
            'name' => $file1->getClientOriginalName(),
            'ext' => $file1->getClientOriginalExtension(),
            'size' => $file1->getSize(),
        ]);

        // Создаем копию файла с другим именем
        $file2 = UploadedFile::fake()->image('photo2.jpg', $file1->getContent());
        $path2 = Storage::disk('public')->putFile('images', $file2);
        $fullPath2 = '/storage/' . $path2;

        $filePath2 = FilePath::createOfFind([
            'path' => $fullPath2,
            'name' => $file2->getClientOriginalName(),
            'ext' => $file2->getClientOriginalExtension(),
            'size' => $file2->getSize(),
        ]);

        $this->assertEquals($filePath1->id, $filePath2->id);
    }

    public function test_deleting_removes_unused_file()
    {
        $file = UploadedFile::fake()->image('photo.jpg');
        $path = Storage::disk('public')->putFile('images', $file);
        $fullPath = '/storage/' . $path;

        $filePath = FilePath::createOfFind([
            'path' => $fullPath,
            'name' => $file->getClientOriginalName(),
            'ext' => $file->getClientOriginalExtension(),
            'size' => $file->getSize(),
        ]);

        $this->assertTrue(Storage::disk('public')->exists($path));

        $filePath->delete();

        $this->assertFalse(Storage::disk('public')->exists($path));
    }

    public function test_updating_path_removes_old_file()
    {
        // Создаем первый файл
        $file1 = UploadedFile::fake()->image('old.jpg');
        $path1 = Storage::disk('public')->putFile('images', $file1);
        $fullPath1 = '/storage/' . $path1;

        $filePath = FilePath::createOfFind([
            'path' => $fullPath1,
            'name' => $file1->getClientOriginalName(),
            'ext' => $file1->getClientOriginalExtension(),
            'size' => $file1->getSize(),
        ]);

        // Создаем новый файл
        $file2 = UploadedFile::fake()->image('new.jpg');
        $path2 = Storage::disk('public')->putFile('images', $file2);
        $fullPath2 = '/storage/' . $path2;

        $filePath->update([
            'path' => $fullPath2,
            'name' => $file2->getClientOriginalName(),
            'ext' => $file2->getClientOriginalExtension(),
            'size' => $file2->getSize(),
        ]);

        $this->assertFalse(Storage::disk('public')->exists($path1));
        $this->assertTrue(Storage::disk('public')->exists($path2));
    }
}
