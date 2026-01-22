<?php

namespace App\Providers;

use App\Models\WorkingPaper;
use App\Policies\WorkingPaperPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        WorkingPaper::class => WorkingPaperPolicy::class,
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
    }
}
