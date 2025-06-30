<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class WalletMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Authentication required'
            ], 401);
        }

        // Check if wallet exists for certain routes
        $walletRequiredRoutes = [
            'wallet.add-money',
            'wallet.transfer',
            'wallet.balance',
            'wallet.transactions'
        ];

        if (in_array($request->route()->getName(), $walletRequiredRoutes)) {
            if (!$user->wallet) {
                return response()->json([
                    'success' => false,
                    'message' => 'Wallet not found. Please create a wallet first.'
                ], 404);
            }
        }

        return $next($request);
    }
}