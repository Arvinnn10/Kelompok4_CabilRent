<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        if (file_exists($helper = app_path('helpers.php'))) {
            require_once $helper;
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Direktif Blade @css('css/file.css') dengan cache-busting otomatis
        Blade::directive('css', function ($expression) {
            return "<?php echo '<link rel=\"stylesheet\" href=\"' . asset_versioned({$expression}) . '\">'; ?>";
        });
    }
}
