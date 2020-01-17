<?php

namespace App\Providers;

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
        Collection::macro('appendToEach', function () {
            $fields = func_get_args();

            return $this->each(function ($model) use ($fields) {
               $model->append(...$fields);
            });
        });

        Collection::macro('hideInEach', function () {
            $fields = func_get_args();

            return $this->each(function ($model) use ($fields) {
                $model->makeHidden(...$fields);
            });
        });
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
    }
}
