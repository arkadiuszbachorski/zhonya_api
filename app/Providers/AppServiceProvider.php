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
                $model->addHidden(...$fields);
            });
        });

        Collection::macro('standardDeviation', function($key = null, $average = null) {
            $values = isset($key) ? $this->pluck($key) : $this;

            $average = $average ?? $values->avg();

            $variance = $values->reduce(function ($carry, $item) use ($average, $key) {
                return $carry + pow($item - $average, 2);
            }, 0) / $this->count();

            return sqrt($variance);
        });

        Collection::macro('quartiles', function($key = null) {
            $length = $this->count();

            $chunked = $this->chunk(ceil($length / 2));

            $firstHalf = $chunked->get(0)->values();
            if ($length % 2 !== 0) {
                $firstHalf->pop();
            }
            $secondHalf = $chunked->get(1)->values();

            $q1 = $firstHalf->median($key);
            $q2 = $this->median($key);
            $q3 = $secondHalf->median($key);

            return compact('q1', 'q2', 'q3');
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
