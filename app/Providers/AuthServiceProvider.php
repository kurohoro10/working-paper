<?php

namespace App\Providers;

use App\Models\Client;
use App\Models\Expense;
use App\Models\User;
use App\Models\WorkingPaper;
use App\Policies\ClientPolicy;
use App\Policies\ExpensePolicy;
use App\Policies\UserPolicy;
use App\Policies\WorkingPaperPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        WorkingPaper::class => WorkingPaperPolicy::class,
        Expense::class      => ExpensePolicy::class,
        User::class         => UserPolicy::class,
        Client::class       => ClientPolicy::class,
    ];

    /**
     * Register services.
     */
    public function register(): void
    {
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        Gate::define('admin', function ($user) {
            return $user->role === 'admin';
        });
    }
}
