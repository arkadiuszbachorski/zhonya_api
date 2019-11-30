<?php

namespace App\Providers;

use App\Observers\TagObserver;
use App\Observers\TaskObserver;
use App\Tag;
use App\Task;
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
    public function boot()
    {
        Tag::observe(TagObserver::class);
        Task::observe(TaskObserver::class);
    }
}
