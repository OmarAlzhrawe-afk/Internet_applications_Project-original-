<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GovermentAgencyController;
use App\Http\Controllers\ManagingComplaintsController;
use App\Http\Controllers\ManagingUsersController;

//Auth Routes
Route::post('login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
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
        Route::post('add_comment_complaint', 'delete'); // override for employee && citizin
        Route::post('add_attachment_complaint', 'delete'); // override for employee && citizin
    });
    // Route::get('reports', [ManageEmployeeTicketsController::class, 'generate_reports']);
    // Route::get('show_logs_statistics', [ManageEmployeeTicketsController::class, 'show_logs_statistics']);
});
// old api file 


// Route::prefix('/client')->group(function () {
//     Route::post('/register', [ClientAuthController::class, 'register']);
//     Route::post('/send_verification_code', [ClientAuthController::class, 'sendVerificationCode']);
//     Route::post('/verify_email', [ClientAuthController::class, 'verifyEmail']);
//     Route::post('/login', [ClientAuthController::class, 'login']);
//     Route::post('/forget_password', [ClientAuthController::class, 'forgetPassword']);
//     Route::middleware(['auth:sanctum', 'api'])->group(function () {
//         Route::post('/reset_password', [ClientAuthController::class, 'resetPassword']);
//         Route::post('/logout', [ClientAuthController::class, 'logout']);
//         // Complaints routes
//         Route::prefix('/complaints')->group(function () {
//             Route::post('/create', [ComplaintController::class, 'createcomplaint']);
//             Route::get('/{id}', [ComplaintController::class, 'getTicket']);
//             Route::get('/getallmytickets', [ComplaintController::class, 'getAllMyTickets']);
//             Route::put('/{id}', [ComplaintController::class, 'updateTicket']);
//             Route::delete('/{id}', [ComplaintController::class, 'deleteTicket']);
//         });
//         // Notifications routes
//         Route::prefix('Notifications')->group(function () {
//             Route::get('/getall', [ClientAuthController::class, 'getAllNotifications']);
//             Route::post('/markasread/{id}', [ClientAuthController::class, 'markAsRead']);
//         });
//     });
// });


// //  employee routes
// Route::prefix('employee')->group(function () {
//     // Employee authentication routes
//     Route::post('/login', [EmployeeAuthController::class, 'login']);
//     Route::post('/logout', [EmployeeAuthController::class, 'logout'])->middleware('auth:api');
//     // Employee ticket management routes
//     Route::middleware('auth:api')->prefix('/Tickets')->group(function () {
//         Route::get('/get_my_tickets', [ManageEmployeeTicketsController::class, 'get_my_tickets']);
//         Route::get('/add_notes_for_tickets', [ManageEmployeeTicketsController::class, '/add_notes_for_tickets']);
//         Route::put('/{id}/update-status', [ManageEmployeeTicketsController::class, 'updateTicketStatus']);
//         Route::post('/send_notify', [ManageEmployeeTicketsController::class, 'send_notify']);
//     });
// });
