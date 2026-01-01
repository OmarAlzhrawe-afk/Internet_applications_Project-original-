<?php

namespace App\Http\Controllers\V2;

use App\Http\Requests\V2\RegisterRequest;
use App\Http\Requests\V2\SendVerficationCodeRequest;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Services\AuthService;
use Illuminate\Support\Facades\Validator;

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
        broadcast(new \App\Events\UserRegister($userData));
        return sendResponse(['user_id' => $userData->id], 201, "Create Account For  " . $userData->First_name . "  Done , please check your Email we send verfy code for you .");
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
        $check = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
            'password' => 'required'
        ]);
        if ($check->fails()) {
            return sendResponse(null, 400, "validation error", $check->errors());
        }
        $data = $request->only('email', 'password');
        $response =  $this->service->login($data);
        // $response['email'] = $request->email;
        // $response['role'] = auth('sanctum')->user()->role ?? null;

        return sendResponse($response['data'] ?? null, $response['code'], $response['message'], $response['status']);
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
