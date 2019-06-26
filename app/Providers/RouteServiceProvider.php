<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    protected $namespace = 'App\Http\Controllers';

    public function map()
    {
        $this->mapApiAuthRoutes();

        $this->mapApiGuestRoutes();

        $this->mapWebRoutes();
    }

    
    protected function mapWebRoutes()
    {
        Route::middleware('web')
             ->namespace($this->namespace)
             ->group(base_path('routes/web.php'));
    }

    protected function mapApiGuestRoutes()
    {
        Route::prefix('api/v1')
             ->name('api.')
             ->middleware(['api', 'guest'])
             ->namespace($this->namespace.'\\Api')
             ->group(base_path('routes/api-guest.php'));
    }

    protected function mapApiAuthRoutes()
    {
        Route::prefix('api/v1')
             ->name('api.')
             ->middleware(['api', 'auth:api'])
             ->namespace($this->namespace.'\\Api')
             ->group(base_path('routes/api-auth.php'));
    }
}
