<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\V1\AuthController;
use App\Http\Controllers\V1\GovermentAgencyController;
use App\Http\Controllers\V1\ManagingComplaintsController;
use App\Http\Controllers\V1\ManagingUsersController;

// testing load balancer api 
Route::get('/test-balancer', function () {
    $server_port = env('APP_PORT', 'N/A');
    if ($server_port === 'N/A' && isset($_SERVER['SERVER_PORT'])) {
        $server_port = $_SERVER['SERVER_PORT'];
    }
    return response()->json([
        'message' => 'تم استلام الطلب بنجاح.',
        'served_by' => 'Server running on port: ' . $server_port
    ]);
});
//Auth
Route::post('login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::post('/get_my_last_activities', [AuthController::class, 'get_my_last_activities'])->middleware('auth:sanctum');
// Notifications routes
Route::prefix('Notifications')->middleware('auth:sanctum')->group(function () {
    Route::get('/getall', [AuthController::class, 'getAllNotifications']);
    Route::post('/markasread/{id}', [AuthController::class, 'markAsRead']);
});
// super Admin apis
Route::prefix('/super_admin')->middleware(['auth:sanctum', 'role:super_admin'])->group(function () {
    Route::controller(GovermentAgencyController::class)->group(function () {
        Route::get('/get_goverment_agency', 'index');
        Route::post('/create_goverment_agency', 'create');
        Route::post('/update_goverment_agency', 'update');
        Route::post('/delete_goverment_agency', 'delete');
    });
    Route::controller(ManagingUsersController::class)->group(function () {
        Route::get('get_users/{role}', 'index'); // override for employee && supervisor && super Admin
        Route::post('create_user', 'create'); // override for employee && supervisor && super Admin
        Route::post('update_user', 'update'); // override for employee && supervisor && super Admin
        Route::post('delete_user', 'delete'); // override for employee && supervisor && super Admin
    });
    Route::controller(ManagingComplaintsController::class)->group(function () {
        Route::get('get_complaints', 'index'); // override for employee && supervisor && super Admin
        Route::post('update_complaint', 'update'); // override for employee && supervisor && super Admin
        Route::post('delete_complaint', 'delete'); // override for employee && supervisor && super Admin
    });
});
//  Supervisor apis
Route::prefix('supervisor')->middleware(['auth:sanctum', 'role:supervisor'])->group(function () {
    Route::controller(ManagingComplaintsController::class)->group(function () {
        Route::get('get_complaints', 'index'); // override for employee && supervisor && super Admin
        Route::post('update_complaint', 'update'); // override for employee && supervisor && super Admin
        Route::post('delete_complaint', 'delete'); // override for employee && supervisor && super Admin
        Route::post('accept_complaint', 'accept_complaint');
    });
    Route::controller(ManagingUsersController::class)->group(function () {
        Route::get('get_employees', 'index');
        Route::post('create_employees', 'create'); // override for supervisor && super Admin
        Route::post('update_employees', 'update'); // override for supervisor && super Admin
        Route::post('delete_employees', 'delete'); // override for supervisor && super Admin
    });

    // Route::get('reports', [ManageEmployeeTicketsController::class, 'generate_reports']);
    // Route::get('show_logs_statistics', [ManageEmployeeTicketsController::class, 'show_logs_statistics']);
});
// Employee apis 
Route::prefix('employee')->middleware(['auth:sanctum', 'role:employee'])->group(function () {
    Route::controller(ManagingComplaintsController::class)->group(function () {
        Route::get('get_complaints', 'index'); // override for employee && supervisor && super Admin
        Route::post('update_complaint', 'update'); // override for employee && supervisor && super Admin
        Route::post('add_comment_complaint', 'add_comment_complaint'); // override for employee && citizin
        Route::post('add_attachment_complaint', 'add_attachment_complaint'); // override for employee && citizin
    });
    // Route::get('reports', [ManageEmployeeTicketsController::class, 'generate_reports']);
    // Route::get('show_logs_statistics', [ManageEmployeeTicketsController::class, 'show_logs_statistics']);
});
// client apis 
Route::prefix('/client')->group(function () {
    Route::controller(AuthController::class)->group(function () {
        Route::post('/register', 'register');
        Route::post('/send_verification_code', 'sendVerificationCode');
        Route::post('/verify_email', 'verifyEmail');
        Route::post('/login', 'login');
        Route::post('/logout', 'logout')->middleware('auth:sanctum');
    });
    Route::middleware(['auth:sanctum', 'role:client'])->group(function () {
        // Complaints routes
        Route::prefix('/complaints')->controller(ManagingComplaintsController::class)->group(function () {
            Route::get('/getall',  'index');
            Route::get('/get/{id}',  'OneComplaint');
            Route::post('/update', 'update');
            Route::post('/createcomplaint', 'create');
            Route::delete('/delete/{id}', 'deleteTicket');
            Route::post('add_comment_complaint', 'add_comment_complaint'); // override for employee && citizin
            Route::post('add_attachment_complaint', 'add_attachment_complaint'); // override for employee && citizin
        });
    });
});
