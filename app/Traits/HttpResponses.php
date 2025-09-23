<?php

namespace App\Traits;

trait HttpResponses {
    
    protected function success($data, string $message = null, int $code = 200)
    {
        return response()->json([
            'status' => 'success',
            'message' => $message,
            'results' => $data
        ], $code);
    }
    
    protected function error($data, string $message = null, int $code)
    {
        return response()->json([
            'status' => 'error',
            'message' => $message,
            'results' => $data
        ], $code);
    }
}