<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Validator;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        Validator::extend('phone', function($attribute, $value, $parameters) {
            return substr($value, 0, 1) > '5';
        });

        $this->linkUploadsDirectory();
    }

    /**
     * On the live server, .htaccess routes /uploads/... straight to the CImage
     * resizer (public/img/webroot/img.php), which is configured (img_config.php)
     * to read sources from public/uploads/. Uploaded files are actually saved to
     * storage/app/uploads (see Storage::disk('uploads')), so without this symlink
     * every freshly uploaded image (logo included) 404s on the live server even
     * though it displays fine locally via the artisan-serve fallback route in
     * routes/web.php. Cheap file_exists check, so this is a no-op once linked.
     */
    protected function linkUploadsDirectory()
    {
        $link = public_path('uploads');
        if (file_exists($link) || !function_exists('symlink')) {
            return;
        }

        try {
            $target = storage_path('app/uploads');
            if (!is_dir($target)) {
                @mkdir($target, 0755, true);
            }
            @symlink($target, $link);
        } catch (\Throwable $e) {
            // Fall back to `php artisan uploads:link` if symlink() is disabled here.
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
