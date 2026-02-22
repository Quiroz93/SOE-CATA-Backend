<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to the "/home" route for your application.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * Define your route model bindings, pattern filters, etc.
     */
    public function boot(): void
    {
        parent::boot();

        $this->routes(function () {
            // Carga rutas API admin
            Route::prefix('api')
                ->middleware('api')
                ->group(base_path('routes/api.php'));

            // Carga rutas públicas versionadas
            Route::middleware('api')
                ->group(base_path('routes/api_v1_public.php'));

            // Carga rutas web
            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });
    }
}
