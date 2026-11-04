<?php

use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

function updateImagePath(UploadedFile $image, ?string $oldImage = null, string $folderName = 'website-image'): string
{
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

function overWriteEnvFile(string $key, $value): void
{
    if (env('DEMO_MODE') === 'On') {
        return; // Prevent changes in demo mode
    }

    $path = base_path('.env');
    if (! file_exists($path)) {
        return;
    }

    // 🔎 Normalize the value
    if (is_bool($value) || strtolower((string) $value) === 'true' || strtolower((string) $value) === 'false') {
        $value = filter_var($value, FILTER_VALIDATE_BOOLEAN) ? 'true' : 'false';
    } elseif (is_null($value) || strtolower((string) $value) === 'null') {
        $value = 'null';
    } elseif (is_int($value) || ctype_digit((string) $value)) {
        $value = (string) intval($value); // integer
    } elseif (is_float($value) || preg_match('/^-?\d+\.\d+$/', (string) $value)) {
        $value = (string) floatval($value); // float
    } else {
        $value = trim((string) $value);
        // If value contains spaces or special characters → wrap in quotes
        if (preg_match('/\s|=/', $value)) {
            $value = '"'.$value.'"';
        }
    }

    // 🔄 Read existing .env into lines
    $env = file($path, FILE_IGNORE_NEW_LINES);
    $keyFound = false;

    foreach ($env as &$line) {
        if (strpos($line, $key.'=') === 0) {
            $line = $key.'='.$value;
            $keyFound = true;
            break;
        }
    }

    if (! $keyFound) {
        $env[] = $key.'='.$value;
    }

    // 💾 Save back
    file_put_contents($path, implode(PHP_EOL, $env).PHP_EOL);
}
