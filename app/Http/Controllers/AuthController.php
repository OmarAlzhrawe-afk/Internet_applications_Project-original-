<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Http\Requests\SendVerficationCodeRequest;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Services\AuthService;

class AuthController extends Controller
{
    private $service;
    public function __construct()
    {
        $this->service = new AuthService();
    }
    public function register(RegisterRequest $request)
    {
        $data = $request->validated();
        $userData =  $this->service->registerclient($data);
        return sendResponse(null, 201, "Create Account For  " . $userData->First_name . "  Done");
    }
    public function sendVerificationCode(SendVerficationCodeRequest $request)
    {
        $data = $request->validated();
        $message =  $this->service->sendClientVerificationCode($data);
        return sendResponse(null, 201, $message);
    }
    public function verifyEmail(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email|exists:users,email',
            'code' => 'required|string|exists:otps,code',
        ]);
        $response =  $this->service->ChechEmailVerification($data);
        return sendResponse(null, $response['code'], $response['message'], $response['status']);
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required'
        ]);
        $response =  $this->service->login($data);
        return sendResponse($response['token'] ?? null, $response['code'], $response['message'], $response['status']);
    }
    public function logout(Request $request)
    {
        if ($request->user()) {
            $request->user()->currentAccessToken()->delete();
            return sendResponse(null, 200, "log out Done", false);
        } else {
            return sendResponse(null, 400, "user not found", false);
        }
    }
}
