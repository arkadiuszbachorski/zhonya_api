<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        //

        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApiRoutes();

    }


    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::group([
            'namespace' => $this->namespace,
            'prefix' => 'api',
            'middleware' => 'api',
        ], function () {

            Route::prefix('auth')
                ->group($this->apiRoutesFile('auth'));

            Route::prefix('user')
                ->middleware('auth:api')
                ->group($this->apiRoutesFile('user'));

            Route::prefix('tag')
                ->middleware('auth:api')
                ->group($this->apiRoutesFile('tag'));

            Route::prefix('task')
                ->middleware('auth:api')
                ->group($this->apiRoutesFile('task'));

            Route::prefix('tag/{tag}/task/{task}')
                ->middleware(['api', 'auth:api', 'can:manage,tag', 'can:manage,task'])
                ->group($this->apiRoutesFile('tag-task'));
        });
    }

    private function apiRoutesFile($fileName)
    {
        return base_path("routes/api/$fileName.php");
    }
}
