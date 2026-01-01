<?php

namespace App\Services;

use App\Models\Otp;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AuthService
{
    // AuthService class content
    public function registerclient(array $data)
    {
        // Registration logic here
        $user = User::create([
            'First_name' => $data['First_name'],
            'Last_name' => $data['Last_name'],
            'email' => $data['email'],
            'phone_number' => $data['phone_number'],
            'password' => bcrypt($data['password']),
            'agency_id' => null,
            'role' => 'client',
        ]);
        return $user;
    }
    public function sendClientVerificationCode(array $data)
    {
        // creation and storing otp
        $identifier = $data['id'];
        $code = rand(100000, 999999);
        Otp::create([
            'user_id' => $identifier,
            'identifier' => $data['type'],
            'code' => $code,
            'purpose' => 'verify_account',
            'expires_at' => now()->addMinutes(10),
        ]);
        $user = User::find($identifier);
        //  Here you would typically send the code via email or SMS
        if ($data['type'] == 'email') {
            // // Send email logic
            // Mail::raw("Your verification code is: $code", function ($message) use ($user) {
            //     $message->to($user->email)
            //         ->subject('Email Verification Code');
            // });
        } elseif ($data['type'] == 'phone') {
            // // Send SMS logic (using a hypothetical SMS service)
            // SmsService::send($user->phone_number, "Your verification code is: $code");
        }
        return "Sending Verify Code Done";
    }
    public function ChechEmailVerification(array $data)
    {
        $response = [];

        $user = User::where('email', $data['email'])->first();

        // Check if email already verified
        if ($user->is_email_verified) {
            $response = [
                'status' => false,
                'message' => 'Email is already verified',
                'code' => 400,
            ];
        }
        // Fetch OTP
        $otp = Otp::where('user_id', $user->id)
            ->where('code', $data['code'])
            ->where('purpose', 'verify_account')
            ->where('identifier', 'email')
            ->where('is_used', false)
            ->where('expires_at', '>', now())
            ->first();

        if (!$otp) {
            $response = [
                'status' => false,
                'message' => 'Invalid or expired code',
                'code' => 400,
            ];
        } else {
            // Mark verified
            $user->update(['email_verified_at' => now()]);
            $otp->update(['is_used' => true]);
            $response = [
                'status' => true,
                'message' => 'Email verified successfully',
                'code' => 200,
            ];
        }
        return $response;
    }
    // public function ChechEmailVerification(array $data)
    // {
    //     $resonse = [];
    //     $user = User::where('email', $data['email'])->first();
    //     // checking if email is already verified
    //     if ($user->is_email_verified) {
    //         $resonse = [
    //             'success' => false,
    //             'message' => 'Email is already verified',
    //             'code' => 400,
    //         ];
    //     }
    //     // fetching code from otps table
    //     $code = Otp::where('identifier', $user->id)
    //         ->where('code', $data['code'])
    //         ->where('type', 'email')
    //         ->where('purpose', 'verify_account')
    //         ->where('is_used', false)
    //         ->where('expires_at', '>', now())
    //         ->first();
    //     // if code is valid , verify email
    //     if ($code != null) {
    //         $user->update(['email_verified_at' => now()]);
    //         $code->update(['is_used' => true]);
    //         $resonse = [
    //             'status' => true,
    //             'message' => ' Email Verfying successfully',
    //             'code' => 200,
    //         ];
    //     }
    //     return $resonse;
    // }
    public function login(array $data)
    {
        $response = [];
        $user = User::where('email', $data['email'])->first();
        if (!$user || !Hash::check($data['password'], $user->password)) {
            $response = [
                'status' => false,
                'message' => 'Incorrect Data',
                'code' => 401,
            ];
        } else if (!$user->email_verified_at && $user->role == 'client') {
            $response = [
                'status' => 'error',
                'message' => 'Account not verified ,Please Verfy your Account then Try Again',
                'code' => 403
            ];
        } else {
            $token = $user->createToken('auth_token')->plainTextToken;
            $response = [
                'status' => true,
                'message' => 'login Done',
                'code' => 200,
                'data' => [
                    'token' => $token,
                    'email' => $user->email,
                    'role' => $user->role,
                    'user_id' => $user->id,
                ],
            ];
        }
        return $response;
    }
    // public function logout(Request $request)
    // {
    //     if ($request->user()) {
    //         if ($request->user()->currentAccessToken()) {
    //             $request->user()->currentAccessToken()->delete();
    //         }
    //     }
    //     $response = [
    //         'status' => true,
    //         'message' => 'Logout successful',
    //         'code' => 201,
    //     ];
    //     // sending resposne 
    //     return $response;
    // }
}
