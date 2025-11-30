<?php
if (!function_exists('sendResponse')) {
    function sendResponse($data, $code, $message = null, $hasError = false)
    {
        $ResponseMessages = [
            200 => 'Success',
            201 => 'Created',
            401 => 'Un Authorized Access',
            403 => 'Not Allowed',
            404 => 'Resource Not Found',
            500 => 'Internal server Error',
        ];
        return response()->json(
            [
                'status' => [
                    'code' => $code,
                    'message' => $message ?? $ResponseMessages[$code],
                    'error' => $hasError
                ],
                'data' => $data
            ],
            200
        );
    }
}
