<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {

        \Illuminate\Support\Facades\Blade::directive('bathText', function ($expression) {
            return "<?php echo App\Helpers\BathTextHelper::convert($expression); ?>";
        });
    }
}
