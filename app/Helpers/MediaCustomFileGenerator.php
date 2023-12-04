<?php

namespace App\Helpers;

use Spatie\MediaLibrary\Conversions\Conversion;
use Spatie\MediaLibrary\Support\FileNamer\FileNamer;
use Illuminate\Support\Str;

class MediaCustomFileGenerator extends FileNamer
{
    public function originalFileName(string $fileName): string
    {
        return Str::uuid()->toString();
    }

    public function conversionFileName(string $fileName, Conversion $conversion): string
    {
        // $strippedFileName = pathinfo($fileName, PATHINFO_FILENAME);
        $strippedFileName = Str::uuid()->toString();

        return "{$strippedFileName}-{$conversion->getName()}";
    }

    public function responsiveFileName(string $fileName): string
    {
        // return pathinfo($fileName, PATHINFO_FILENAME);
        return Str::uuid()->toString();
    }
}
