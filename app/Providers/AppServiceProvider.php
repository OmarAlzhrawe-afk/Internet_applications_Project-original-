<?php

namespace App\Providers;

use App\Models\Complaint;
use App\Models\GovernmentAgencie;
use App\Models\User;
use App\Policies\ComplaintPolicy;
use App\Policies\GovernmentAgenciePolicy;
use App\Policies\UserPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }
    public function boot(): void
    {
        Gate::policy(Complaint::class, ComplaintPolicy::class);
        Gate::policy(GovernmentAgencie::class, GovernmentAgenciePolicy::class);
        Gate::policy(User::class, UserPolicy::class);
    }
}
