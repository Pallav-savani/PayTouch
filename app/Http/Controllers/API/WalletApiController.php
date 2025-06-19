<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\MobiKwikService;
use App\Helpers\PaymentHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;

class WalletApiController extends Controller
{
    private MobiKwikService $mobikwikService;

    public function __construct(MobiKwikService $mobikwikService)
    {
        $this->mobikwikService = $mobikwikService;
    }

    public function getBalance(): JsonResponse
    {
        $user = Auth::user();
        
        return response()->json([
            'success' => true,
            'data' => [
                'balance' => $user->wallet_balance,
                'formatted_balance' => PaymentHelper::formatCurrency($user->wallet_balance)
            ]
        ]);
    }

    public function getPaymentBreakdown(Request $request): JsonResponse
    {
        $request->validate([
            'amount' => 'required|numeric|min:1'
        ]);

        $user = Auth::user();
        $breakdown = PaymentHelper::calculatePaymentBreakdown($user, $request->amount);

        return response()->json([
            'success' => true,
            'data' => $breakdown
        ]);
    }

    public function processPayment(Request $request): JsonResponse
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'description' => 'nullable|string|max:255',
        ]);

        $user = Auth::user();
        $result = $this->mobikwikService->processPayment(
            $user,
            $request->amount,
            $request->description
        );

        return response()->json($result);
    }

    public function getTransactionHistory(Request $request): JsonResponse
    {
        $user = Auth::user();
        $perPage = $request->input('per_page', 20);
        
        $transactions = $user->walletTransactions()
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $transactions
        ]);
    }

    public function getTransactionStatus(Request $request): JsonResponse
    {
        $request->validate([
            'transaction_id' => 'required|string'
        ]);

        $transaction = PaymentHelper::getTransactionStatus($request->transaction_id);

        if (!$transaction) {
            return response()->json([
                'success' => false,
                'message' => 'Transaction not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $transaction
        ]);
    }
}