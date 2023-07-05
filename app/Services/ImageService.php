<?php

namespace App\Services;

use App\Models\Attachment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ImageService
{
    private UploadedFile $file;

    private Model $model;

    public function upload(Model $model, UploadedFile $file): self
    {
        $this->file = $file;

        $this->model = $model;

        if (!$this->validation()) {
            throw new \Exception('Invalid image file type.');
        }

        return $this;
    }


    public function save(): Attachment
    {
        $attachment = Attachment::updateOrCreate([
            'resource_type' => $this->model->getMorphClass(),
            'resource_id' => $this->model->__get('id'),
            'path' => date('y/m/d/h'),
            'name' => $this->generateName($this->file->getClientOriginalName()),
            'original_name' => $this->file->getClientOriginalName(),
            'extension' => $this->file->getClientOriginalExtension(),
        ]);

        $this->putFileAs($attachment, $this->file);

        return $attachment;
    }

    public function putFileAs(Attachment $attachment, UploadedFile $file): void
    {
        Storage::disk(Attachment::DISK)
            ->putFileAs(
                $attachment->getStoragePath(),
                $file,
                $attachment->getStorageFileName(),
            );
    }

    public function generateName(string $postFix): string
    {
        return sha1(uniqid('', true).$postFix);
    }

    private function validation(): bool
    {
        return in_array($this->file->getClientOriginalExtension(), [
            'jpeg',
            'png',
            'jpg',
            'gif'
        ]);
    }
}
