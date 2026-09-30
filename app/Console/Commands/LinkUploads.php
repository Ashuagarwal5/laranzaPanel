<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class LinkUploads extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'uploads:link';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Symlink public/uploads to storage/app/uploads so the live-server image resizer (CImage, configured to read from public/uploads) can see files the app saves via Storage::disk(\'uploads\')';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $link = public_path('uploads');
        $target = storage_path('app/uploads');

        if (file_exists($link)) {
            if (is_link($link) && realpath(readlink($link)) === realpath($target)) {
                $this->info('public/uploads is already linked to storage/app/uploads.');
                return 0;
            }

            $this->error('public/uploads already exists and is not the expected symlink - remove or rename it first, then re-run this command.');
            return 1;
        }

        if (!is_dir($target)) {
            @mkdir($target, 0755, true);
        }

        symlink($target, $link);

        $this->info('Linked public/uploads -> storage/app/uploads.');
        return 0;
    }
}
