<?php

namespace App\Services;

use App\Models\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class LogService
{
    public static function logAction(string $action, array $data = [])
    {
        try {
            Log::create([
                'user_id' => Auth::id(), // Null if not logged in
                'action' => $action,
                'data' => $data,
                'ip_address' => Request::ip(),
            ]);
        } catch (\Exception $e) {
            \Log::error('Failed to write log: ' . $e->getMessage());
        }
    }
}
