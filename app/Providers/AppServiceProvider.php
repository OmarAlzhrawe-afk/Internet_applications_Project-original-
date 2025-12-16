<?php

namespace App\Providers;

use App\contracts\ComplaintManagmentInterface;
use App\contracts\ComplaintManagmentInterfaceForEmployee;
use App\contracts\GovermentAgencyInterface;
use App\contracts\UserManagingInterface;
use App\Models\Complaint;
use App\Models\GovernmentAgencie;
use App\Models\User;
use App\Policies\ComplaintPolicy;
use App\Policies\GovernmentAgenciePolicy;
use App\Policies\UserPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use App\Services\interfaces\ComplaintServiceInterface;
use App\Services\ComplaintService;
use App\Services\Aspects\LoggingComplaintDecorator;
use App\Services\complaintservices\CitizenComplaintComplaintManagment;
use App\Services\complaintservices\CitizenComplaintManagment;
use App\Services\complaintservices\EmployeeComplaintManagment;
use App\Services\userservices\SuperAdminUserManagmentService;
use App\Services\userservices\SupervisorAdminUserManagmentService;
use App\Services\govermentservices\crudservice;
use App\Services\complaintservices\SuperAdminComplaintManagment;
use App\Services\complaintservices\SupervisorAdminComplaintManagment;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {

        $this->app->bind(GovermentAgencyInterface::class, function ($app) {
            return new crudservice();
        });

        $this->app->bind(UserManagingInterface::class, function ($app) {
            $user = auth('sanctum')->user();
            if (is_null($user)) {
                throw new \RuntimeException("User not authenticated for User Management Service resolution.");
            }
            if ($user->role === 'super_admin') {
                return new SuperAdminUserManagmentService();
            } elseif ($user->role == 'supervisor') {
                return new SupervisorAdminUserManagmentService();
            }
        });
        $this->app->bind(ComplaintManagmentInterface::class, function ($app) {
            $user = auth('sanctum')->user();

            if ($user->role === 'super_admin') {
                return new SuperAdminComplaintManagment();
            } elseif ($user->role == 'supervisor') {
                return new SupervisorAdminComplaintManagment();
            } elseif ($user->role == 'employee') {
                return new EmployeeComplaintManagment();
            } elseif ($user->role == 'client') {
                return new CitizenComplaintManagment();
            }
        });
    }

    public function boot(): void
    {
        Gate::policy(Complaint::class, ComplaintPolicy::class);
        Gate::policy(GovernmentAgencie::class, GovernmentAgenciePolicy::class);
        Gate::policy(User::class, UserPolicy::class);
        // $this->app->singleton(ComplaintServiceInterface::class, function ($app) {

        //     // 1. إنشاء الخدمة الأساسية (Core Service)
        //     $coreService = $app->make(ComplaintService::class);

        //     // 2. تزيين الخدمة الأساسية بالجانب (Aspect)
        //     $loggingDecorator = new LoggingComplaintDecorator($coreService);

        //     // 3. يمكنك إضافة المزيد من الجوانب هنا
        //     // $securityDecorator = new SecurityComplaintDecorator($loggingDecorator);
        //     // return $securityDecorator;

        //     return $loggingDecorator;
        // });
    }
}
