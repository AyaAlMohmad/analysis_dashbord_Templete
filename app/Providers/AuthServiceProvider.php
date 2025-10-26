<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     */
    // public function boot(): void
    // {
    //     $this->registerPolicies();

    //     //
    // }
      public function boot()
    {
        // تعريف الـ Gates
        Gate::define('edit_project_plans', function ($user, $projectPlan = null) {
            // إذا كان لديه صلاحية التعديل
            if ($user->hasPermission('edit_project_plans')) {
                return true;
            }

            // إذا كان مسؤول عن هذا البند
            if ($projectPlan && $user->name == $projectPlan->responsible) {
                return true;
            }

            return false;
        });

        Gate::define('delete_project_plans', function ($user) {
            return $user->hasPermission('delete_project_plans');
        });

        Gate::define('create_project_plans', function ($user) {
            return $user->hasPermission('create_project_plans');
        });

        Gate::define('mark_completed', function ($user, $projectPlan = null) {
            if ($user->hasPermission('mark_completed')) {
                return true;
            }

            if ($projectPlan && $user->name == $projectPlan->responsible) {
                return true;
            }

            return false;
        });
    }
}
