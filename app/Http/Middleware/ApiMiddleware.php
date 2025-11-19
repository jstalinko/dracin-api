<?php

namespace App\Http\Middleware;

use App\Models\Apikey;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Carbon\Carbon;

class ApiMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Ambil API Key dari header atau query
        $apiKey = $request->header('X-JDIGTAL-APIKEY') 
                  ?? $request->query('apikey');

        if (!$apiKey) {
            return response()->json([
                'success' => false,
                'message' => 'API Key is required.'
            ], 401);
        }

        // Cari API Key di database
        $record = Apikey::where('apikey', $apiKey)
            ->where('active', 1)
            ->where('start_at', '<=', Carbon::now())
            ->where('end_at', '>=', Carbon::now())
            ->first();

        if (!$record) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or expired API Key.'
            ], 401);
        }

        // Set user ke request (optional)
      //  $request->merge(['auth_user' => $record->user]);

        return $next($request);
    }
}
