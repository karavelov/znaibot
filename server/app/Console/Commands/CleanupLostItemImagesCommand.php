<?php

namespace App\Console\Commands;

use App\Models\LostItem;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class CleanupLostItemImagesCommand extends Command
{
    protected $signature = 'lost-items:cleanup-images {--dry-run : Show files that would be deleted without deleting them}';

    protected $description = 'Delete orphaned lost-item image files from public/uploads/lost-items';

    public function handle(): int
    {
        $directory = public_path('uploads/lost-items');

        if (! File::isDirectory($directory)) {
            $this->warn('Directory not found: ' . $directory);
            return self::SUCCESS;
        }

        $referencedPaths = LostItem::withTrashed()
            ->whereNotNull('image_path')
            ->pluck('image_path')
            ->filter()
            ->map(function ($path) {
                $normalized = trim((string) $path);
                if ($normalized === '') {
                    return null;
                }

                if (Str::startsWith($normalized, ['http://', 'https://'])) {
                    $parsedPath = parse_url($normalized, PHP_URL_PATH);
                    if (! is_string($parsedPath) || trim($parsedPath) === '') {
                        return null;
                    }
                    $normalized = ltrim($parsedPath, '/');
                }

                $normalized = ltrim($normalized, '/');

                if (Str::startsWith($normalized, 'uploads/lost-items/')) {
                    return basename($normalized);
                }

                if (Str::startsWith($normalized, 'lost-items/')) {
                    return basename($normalized);
                }

                if (Str::startsWith($normalized, 'storage/lost-items/')) {
                    return basename($normalized);
                }

                return basename($normalized);
            })
            ->filter()
            ->unique()
            ->values()
            ->flip();

        $files = File::files($directory);
        $orphanFiles = [];

        foreach ($files as $file) {
            $name = $file->getFilename();
            if (! $referencedPaths->has($name)) {
                $orphanFiles[] = $file->getPathname();
            }
        }

        if (empty($orphanFiles)) {
            $this->info('No orphaned files found.');
            return self::SUCCESS;
        }

        $dryRun = (bool) $this->option('dry-run');

        if ($dryRun) {
            $this->warn('Dry run mode: no files will be deleted.');
        }

        foreach ($orphanFiles as $path) {
            if ($dryRun) {
                $this->line('[DRY-RUN] ' . $path);
                continue;
            }

            try {
                File::delete($path);
                $this->line('[DELETED] ' . $path);
            } catch (\Throwable $e) {
                $this->error('[FAILED] ' . $path . ' => ' . $e->getMessage());
            }
        }

        $this->info('Orphaned files found: ' . count($orphanFiles));

        return self::SUCCESS;
    }
}
