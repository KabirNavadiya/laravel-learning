<?php

declare(strict_types=1);

namespace Tests\Unit;

use Illuminate\Support\Facades\File;
use Tests\TestCase;

class StrictTypesTest extends TestCase
{
    /**
     * Test that all PHP class files declare strict types.
     */
    public function test_all_class_files_declare_strict_types(): void
    {
        $appPath = base_path('app');
        $phpFiles = File::allFiles($appPath);

        $filesMissingStrictTypes = [];

        foreach ($phpFiles as $file) {
            // Convert SplFileInfo to string path for File::get()
            $contents = File::get($file->getPathname());
            if (! str_contains($contents, 'declare(strict_types=1);')) {
                $filesMissingStrictTypes[] = $file->getRelativePathname();
            }
        }

        $this->assertEmpty(
            $filesMissingStrictTypes,
            'The following files are missing strict type declarations: '.implode(', ', $filesMissingStrictTypes)
        );
    }
}
