<?php

use App\Services\Admin\SettingService;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

function updateImagePath(
    UploadedFile $image,
    ?string $oldImage = null,
    string $folderName = 'website-image',
    string $disk = 'public',
): string {
    if ($disk === 'local') {
        $imagePath = $image->store(trim($folderName, '/'), $disk);

        if ($imagePath === false) {
            throw new RuntimeException('Unable to store the uploaded image.');
        }

        if ($oldImage !== null && $oldImage !== $imagePath) {
            $storage = Storage::disk($disk);

            if ($storage->exists($oldImage) && ! $storage->delete($oldImage)) {
                $storage->delete($imagePath);

                throw new RuntimeException('Unable to remove the previous image.');
            }
        }

        return $imagePath;
    }

    if ($disk !== 'public') {
        throw new InvalidArgumentException('Images may only be stored on the public or local disk.');
    }

    $relativeDirectory = 'uploads/'.trim($folderName, '/').'/';
    $absoluteDirectory = public_path($relativeDirectory);

    if (! is_dir($absoluteDirectory) && ! mkdir($absoluteDirectory, 0775, true) && ! is_dir($absoluteDirectory)) {
        throw new RuntimeException('Unable to create the image upload directory.');
    }

    $imageName = Carbon::now()->toDateString().'-'.Str::uuid().'.'.$image->getClientOriginalExtension();
    $image->move($absoluteDirectory, $imageName);
    $imagePath = $relativeDirectory.$imageName;

    if ($oldImage !== null && $oldImage !== $imagePath) {
        $oldImagePath = public_path($oldImage);

        if (is_file($oldImagePath) && ! unlink($oldImagePath)) {
            unlink(public_path($imagePath));

            throw new RuntimeException('Unable to remove the previous image.');
        }
    }

    return $imagePath;
}

function overWriteEnvFile(string $key, string|int|bool|null $value): bool
{
    if (env('DEMO_MODE') === 'On') {
        return false;
    }

    $path = base_path('.env');
    if (! file_exists($path)) {
        throw new RuntimeException('Unable to update settings because the environment file does not exist.');
    }

    if ($value === null) {
        $serializedValue = 'null';
    } elseif (is_bool($value)) {
        $serializedValue = $value ? 'true' : 'false';
    } else {
        $stringValue = (string) $value;
        if (preg_match('/[\r\n]/', $stringValue) === 1) {
            throw new InvalidArgumentException('Environment values cannot contain line breaks.');
        }

        $serializedValue = '"'.str_replace(['\\', '"', '$'], ['\\\\', '\\"', '\\$'], $stringValue).'"';
    }

    $env = file($path, FILE_IGNORE_NEW_LINES);
    if ($env === false) {
        throw new RuntimeException('Unable to read the environment file.');
    }

    $keyFound = false;

    foreach ($env as &$line) {
        if (preg_match('/^\s*'.preg_quote($key, '/').'\s*=/', $line) === 1) {
            $line = $key.'='.$serializedValue;
            $keyFound = true;
            break;
        }
    }

    if (! $keyFound) {
        $env[] = $key.'='.$serializedValue;
    }

    if (file_put_contents($path, implode(PHP_EOL, $env).PHP_EOL, LOCK_EX) === false) {
        throw new RuntimeException('Unable to write the environment file.');
    }

    return true;
}

function getSetting(string $key): ?string
{
    return app(SettingService::class)->getSettings([$key])[$key] ?? null;
}
