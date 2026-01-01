<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Http\Requests\SendVerficationCodeRequest;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Services\AuthService;
use Illuminate\Support\Facades\Validator;
use Spatie\Activitylog\Models\Activity;

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
    public function get_my_last_activities()
    {
        // 1. التأكد من أن المستخدم مسجل دخول
        $user = auth('sanctum')->user();

        // 2. جلب آخر 10 سجلات نشاط
        $activities = \Spatie\Activitylog\Models\Activity::where('causer_id', $user->id)
            ->where('causer_type', get_class($user)) // لضمان دقة البحث لو وجد أكثر من نوع مستخدم
            ->latest() // لترتيبهم من الأحدث (الأعلى) إلى الأقدم
            ->take(10) // تحديد العدد بـ 10 فقط
            ->get();
        if (!$activities) {
            return response()->json(['message' => 'No activity found']);
        }
        // 3. تنسيق البيانات لإرجاع ما تحتاجه فقط (اختياري لجعل الـ JSON نظيفاً)
        $formattedActivities = $activities->map(function ($activity) {
            return [
                'id' => $activity->id,
                'event' => $activity->event, // مثلاً: Delete User
                'description' => $activity->description, // نص اللوج الذي كتبته
                'properties' => $activity->properties, // أي بيانات إضافية
                'created_at' => $activity->created_at->format('Y-m-d H:i:s'),
                'time_ago' => $activity->created_at->diffForHumans(), // "منذ 5 دقائق"
            ];
        });
        return response()->json([
            'status' => 'success',
            'count' => $formattedActivities->count(),
            'activities' => $formattedActivities
        ]);
    }
}
