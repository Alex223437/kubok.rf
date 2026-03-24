<?php

namespace Tests\Unit;

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

    public function test_storage_path()
    {
        $file1 = UploadedFile::fake()->image('photo1.jpg', 1, 1);
        $path1 = Storage::disk('public')->putFile('img', $file1);
        $this->assertTrue(Storage::disk('public')->exists($path1));

        $url1 = Storage::url($path1);
        $path = str_replace('/storage/', '', $url1);
        $this->assertTrue(Storage::disk('public')->exists($path));
    }

    public function test_create_of_find_returns_existing_file_with_same_hash()
    {
        // Создаем первый файл
        $file1 = UploadedFile::fake()->image('photo1.jpg');
        $path1 = Storage::disk('public')->putFile('images', $file1);
        $fullPath1 = Storage::url($path1);

        $filePath1 = FilePath::createOfFind([
            'path' => $fullPath1,
            'name' => $file1->getClientOriginalName(),
            'ext' => $file1->getClientOriginalExtension(),
            'size' => $file1->getSize(),
        ]);

        // Создаем копию файла с другим именем
        $file2 = UploadedFile::fake()->image('photo2.jpg');
        $path2 = Storage::disk('public')->putFile('images', $file2);
        $fullPath2 = Storage::url($path2);

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
        $publicPath = Storage::url($path);

        $filePath = FilePath::createOfFind([
            'path' => $publicPath,
            'name' => $file->getClientOriginalName(),
            'ext' => $file->getClientOriginalExtension(),
            'size' => $file->getSize(),
        ]);

        $this->assertTrue(Storage::disk('public')->exists($path));

        $filePath->delete();

        $this->assertFalse(Storage::disk('public')->exists($path));
    }

    public function test_deleting_not_removes_used_file()
    {
        // Создаем файл
        $file = UploadedFile::fake()->image('photo.jpg');
        $path = Storage::disk('public')->putFile('images', $file);
        $publicPath = Storage::url($path);

        // Создаем первую запись
        $filePath1 = FilePath::create([
            'path' => $publicPath,
            'name' => $file->getClientOriginalName(),
            'ext' => $file->getClientOriginalExtension(),
            'size' => $file->getSize(),
        ]);

        $this->assertTrue(Storage::disk('public')->exists($path));

        // Создаем вторую запись с тем же файлом
        $filePath2 = FilePath::create([
            'path' => $publicPath,
            'name' => $file->getClientOriginalName(),
            'ext' => $file->getClientOriginalExtension(),
            'size' => $file->getSize(),
        ]);

        // Проверяем что записи ссылаются на один файл
        $this->assertEquals($filePath1->hash, $filePath2->hash);
        $this->assertNotEquals($filePath1->id, $filePath2->id);
        $this->assertTrue(Storage::disk('public')->exists($path));

        // Удаляем первую запись
        $filePath1->delete();

        // Проверяем что файл все еще существует
        $this->assertTrue(Storage::disk('public')->exists($path));

        $filePath2->delete();
    }


    public function test_updating_removes_old_file()
    {
        // Создаем первый файл
        $file1 = UploadedFile::fake()->image('old.jpg', 11, 11);
        $path1 = Storage::disk('public')->putFile('images', $file1);
        $publicPath1 = Storage::url($path1);

        $filePath = FilePath::create([
            'path' => $publicPath1,
            'name' => $file1->getClientOriginalName(),
            'ext' => $file1->getClientOriginalExtension(),
            'size' => $file1->getSize(),
        ]);

        $this->assertTrue(Storage::disk('public')->exists($path1));

        // Создаем новый файл
        $file2 = UploadedFile::fake()->image('new.jpg', 22, 22);
        $path2 = Storage::disk('public')->putFile('images', $file2);
        $publicPath2 = Storage::url($path2);

        $filePath->update([
            'path' => $publicPath2,
            'name' => $file2->getClientOriginalName(),
            'ext' => $file2->getClientOriginalExtension(),
            'size' => $file2->getSize(),
        ]);

        $this->assertTrue(Storage::disk('public')->exists($path2));
        $this->assertFalse(Storage::disk('public')->exists($path1));

        $filePath->delete();
    }
}
