<?php

namespace App\Providers;

use App\Attempt;
use App\Observers\AttemptObserver;
use App\Observers\TagObserver;
use App\Observers\TaskObserver;
use App\Observers\UserObserver;
use App\Tag;
use App\Task;
use App\User;
use Illuminate\Support\Collection;
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
        User::observe(UserObserver::class);
        Attempt::observe(AttemptObserver::class);
    }
}
