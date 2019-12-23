<?php

namespace App\Providers;

use App\Attempt;
use App\Policies\AttemptPolicy;
use App\Policies\TagPolicy;
use App\Policies\TaskPolicy;
use App\Tag;
use App\Task;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
         Tag::class => TagPolicy::class,
         Task::class => TaskPolicy::class,
         Attempt::class => AttemptPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Passport::routes();
    }
}
