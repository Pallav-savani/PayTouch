<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ValidatePaymentRequest
{
    public function handle(Request $request, Closure $next)
    {
        // Validate amount
        if ($request->has('amount')) {
            $amount = $request->input('amount');
            if (!is_numeric($amount) || $amount <= 0) {
                return response()->json(['error' => 'Invalid amount'], 400);
            }
        }

        // Rate limiting check
        $key = 'payment_attempts_' . ($request->user()->id ?? $request->ip());
        $attempts = cache()->get($key, 0);
        
        if ($attempts >= 5) {
            Log::warning('Payment rate limit exceeded', [
                'user_id' => $request->user()->id ?? null,
                'ip' => $request->ip()
            ]);
            return response()->json(['error' => 'Too many payment attempts'], 429);
        }
        
        cache()->put($key, $attempts + 1, now()->addMinutes(10));

        return $next($request);
    }
}