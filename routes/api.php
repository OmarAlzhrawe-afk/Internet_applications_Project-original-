<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GovermentAgencyController;

//super admin Routes
Route::post('login', [AuthController::class, 'login']);
// super Admin apis
Route::prefix('/super_admin')->middleware(['auth:sanctum', 'role:super_admin'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::controller(GovermentAgencyController::class)->group(function () {
        Route::get('/get_goverment_agency', 'index');
        Route::post('/create_goverment_agency', 'create');
        Route::post('/update_goverment_agency', 'update');
        Route::post('/delete_goverment_agency', 'delete');
    });
    // Route::controller(ManagingUsersController::class)->group(function () {
    //     Route::get('get_users\{role}', 'index');
    //     Route::post('create_user', 'create');
    //     Route::post('update_user', 'update');
    //     Route::post('delete_user', 'delete');
    // });
    // Route::controller(ManagingComplaintsController::class)->group(function () {
    //     Route::get('get_complaints', 'index');
    //     Route::post('create_complaints', 'create');
    //     Route::post('update_complaints', 'update');
    //     Route::post('delete_complaints', 'delete');
    // });
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
// //  admin routes
// Route::prefix('admin')->group(function () {
//     Route::get('get_all_tickets', [ManageEmployeeTicketsController::class, 'get_all_tickets']);
//     Route::prefix('users')->group(function () {
//         Route::get('/index', [ManageEmployeeTicketsController::class, 'get_all_users']);
//         Route::post('/create', [ManageEmployeeTicketsController::class, 'create_user']);
//         Route::put('/update', [ManageEmployeeTicketsController::class, 'update_user']);
//         Route::delete('/delete', [ManageEmployeeTicketsController::class, 'delete_user']);
//     });
//     Route::prefix('roles-permissions')->group(function () {
//         Route::get('/index', [ManageEmployeeTicketsController::class, 'get_all_roles']);
//         Route::post('/create', [ManageEmployeeTicketsController::class, 'create_role']);
//         Route::put('/update', [ManageEmployeeTicketsController::class, 'update_role']);
//         Route::delete('/delete', [ManageEmployeeTicketsController::class, 'delete_role']);
//     });
//     Route::get('reports', [ManageEmployeeTicketsController::class, 'generate_reports']);
//     Route::get('show_logs_statistics', [ManageEmployeeTicketsController::class, 'show_logs_statistics']);
// });
