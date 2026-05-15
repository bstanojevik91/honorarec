<?php

namespace App\Support;

use App\Models\JobListing;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class JobImageManager
{
    private const DIRECTORY = 'storage/job-images';

    public function store(UploadedFile $file, ?JobListing $job = null): string
    {
        $directory = public_path(self::DIRECTORY);

        if (! File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        $extension = strtolower($file->getClientOriginalExtension() ?: $file->extension() ?: 'jpg');
        $filename = 'job-'.($job?->id ?? 'new').'-'.Str::uuid().'.'.$extension;

        while (File::exists($directory.'/'.$filename)) {
            $filename = 'job-'.($job?->id ?? 'new').'-'.Str::uuid().'.'.$extension;
        }

        $file->move($directory, $filename);

        return self::DIRECTORY.'/'.$filename;
    }

    public function delete(?string $jobImagePath): void
    {
        if (blank($jobImagePath)) {
            return;
        }

        $relativePath = ltrim(trim((string) $jobImagePath), '/');

        if (! str_starts_with($relativePath, self::DIRECTORY.'/')) {
            return;
        }

        $fullPath = public_path($relativePath);

        if (File::exists($fullPath)) {
            File::delete($fullPath);
        }
    }
}
