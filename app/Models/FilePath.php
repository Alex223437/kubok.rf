<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Модель для хранения информации о файлах
 * @property string $path Путь к файлу
 * @property string $name Оригинальное имя файла
 * @property string $ext Расширение файла
 * @property int $size Размер файла в байтах
 * @property string $hash MD5-хеш файла
 */
class FilePath extends Model
{
    protected $table = 'files';
    protected $fillable = [
        'path',
        'name',
        'ext',
        'size',
        'hash',
    ];

    protected static function booted()
    {
        static::creating(function (FilePath $model) {
            if (empty($model->hash)) {
                $model->hash = static::getHashByPath($model->path);
            }
        });

        // Обработка событий перед сохранением модели
        static::updating(function (FilePath $model) {
            // Вычисляем хеш для нового файла
            if ($model->isDirty('path') || empty($model->hash)) {
                $model->hash = static::getHashByPath($model->path);
            }
            if ($model->isDirty('hash') || $model->isDirty('path')) {
                // Получаем старый путь и хеш
                $oldPath = (string)$model->getOriginal('path');
                $oldHash = (string)$model->getOriginal('hash');
                // Удаляем старый файл если он из хранилища и больше не используется
                if ($oldHash && Str::startsWith($oldPath, '/storage/')) {
                    $oldStoragePath = static::getStoragePath($oldPath);
                    // Проверяем есть ли другие записи с таким же хешем
                    $count = static::where('hash', $oldHash)->where('id', '!=', $model->id)->count();
                    // Если нет других записей с таким хешем - удаляем файл
                    if ($count === 0 && Storage::disk('public')->exists($oldStoragePath)) {
                        Storage::disk('public')->delete($oldStoragePath);
                    }
                }
            }
        });

        // Обработка событий при удалении модели
        static::deleting(function (FilePath $model) {
            $model->safeRemoveFile();
        });
    }

    /**
     * Создает новую запись или возвращает существующую по хешу файла
     * @param array $attributes Атрибуты файла
     * @return static
     */
    public static function createOfFind(array $attributes): static
    {
        if (!empty($attributes['path'])) {
            $hash = static::getHashByPath($attributes['path']);
            if ($hash) {
                $existing = static::where('hash', $hash)->first();
                if ($existing) {
                    // Проверяем существование файла для найденной записи
                    $path = public_path($existing->path);
                    if (!file_exists($path)) {
                        $existing->update($attributes);
                    }
                    return $existing;
                }
                $attributes['hash'] = $hash;
            }
        }

        return static::create($attributes);
    }

    public function isFromStorage(): bool
    {
        return Str::startsWith($this->path, '/storage/');
    }

    public function removeFile(): void
    {
        if (!$this->isFromStorage()) {
            return;
        }
        $path = static::getStoragePath($this->path);
        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }

    public function safeRemoveFile(): void
    {
        if ($this->isFromStorage()) {
            // Удаляем файл если он больше нигде не используется
            $count = static::where('hash', $this->hash)->where('id', '!=', $this->id)->count();
            if ($count === 0) {
                $this->removeFile();
            }
        }
    }

    public static function getStoragePath(string $path): string
    {
        return str_replace('/storage', '', $path);
    }

    /**
     * Возвращает отформатированный размер файла (KB или MB)
     * @return string
     */
    public function getFormattedSize(): string
    {
        $size = $this->size;

        if (!$size && $this->path) {
            $path = public_path($this->path);
            $size = file_exists($path) ? filesize($path) : 0;
        }

        if ($size < 1024 * 1024) {
            return number_format($size / 1024, 1) . ' KB';
        }

        return number_format($size / 1024 / 1024, 1) . ' MB';
    }

    /**
     * Вычисляет MD5-хеш файла по указанному пути
     * @param string $path Путь к файлу
     * @return string|null Хеш файла или null если файл не существует
     */
    public static function getHashByPath(string $path): ?string
    {
        $fullPath = public_path($path);
        if (file_exists($fullPath)) {
            return md5_file($fullPath);
        } else {
            throw new \Exception('File not found: ' . $path);
        }
    }
}
