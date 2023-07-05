<?php

namespace App\Models;

use App\Services\ImageService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class Attachment extends Model
{
    use HasFactory;

    const PATH_FOLDER = 'attachments';
    const DISK = 'public';

    protected $fillable = [
        'resource_type',
        'resource_id',
        'name',
        'original_name',
        'path',
        'group',
        'extension'
    ];

    public function getUrlAttribute(): ?string
    {
        return $this->url();
    }

    public function url(): ?string
    {
        return Storage::disk(self::DISK)
            ->url($this->getFullPath());
    }

    public function getAbsolutePath(): string
    {
        return Storage::disk(self::DISK)->path($this->getFullPath());
    }

    public function getFullPath(): string
    {
        return implode('/', [
            $this->getStoragePath(),
            $this->getStorageFileName(),
        ]);
    }

    public function getStorageFileName(): string
    {
        return implode('.', [
            $this->name,
            $this->extension,
        ]);
    }

    public function getStoragePath(): string
    {
        return implode('/', [
            self::PATH_FOLDER,
            $this->path,
        ]);
    }

    public function createPreview(): Attachment
    {
        $preview = $this->replicate();

        $service = app(ImageService::class);

        $preview->group = 'preview';
        $fileName = $service->generateName($preview->name);

        Image::make($this->getAbsolutePath())
            ->resize(150, 150)
            ->save(Str::replaceLast($this->name, $fileName, $this->getAbsolutePath()));

        $preview->name = $fileName;
        $preview->save();

        return $preview;
    }
}
