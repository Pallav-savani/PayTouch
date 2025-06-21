<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;

use App\Models\WalletTransaction;
use App\Services\MobiKwikService;
use App\Services\WalletService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use App\Models\Wallet;

class WalletController extends Controller
{
    private MobiKwikService $mobikwikService;
    private WalletService $walletService;

    public function __construct(MobiKwikService $mobikwikService, WalletService $walletService)
    {
        $this->mobikwikService = $mobikwikService;
        $this->walletService = $walletService;
    }

    public function index()
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated'
                ], 401);
            }

            $wallet = $this->walletService->getOrCreateWallet($user);

            $transactions = WalletTransaction::where('user_id', $user->id)
                                  ->orderBy('created_at', 'desc')
                                  ->paginate(10);

            return response()->json([
                'success' => true,
                'data' => [
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'wallet_balance' => $user->wallet_balance ?? 0
                    ],
                    'wallet' => [
                        'id' => $wallet->id,
                        'wallet_id' => $wallet->wallet_id,
                        'balance' => $wallet->balance,
                        'status' => $wallet->status,
                        'is_kyc_verified' => $wallet->is_kyc_verified,
                        'daily_limit' => $wallet->daily_limit,
                        'monthly_limit' => $wallet->monthly_limit,
                        'total_loaded' => $wallet->total_loaded,
                        'total_spent' => $wallet->total_spent
                    ],
                    'transactions' => $transactions
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Wallet index error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to load wallet data'
            ], 500);
        }
    }

    public function getUserData()
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated'
                ], 401);
            }

            $wallet = $this->walletService->getOrCreateWallet($user);

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'wallet_balance' => $user->wallet_balance ?? 0,
                    'mobile' => $user->mobile ?? $user->phone ?? null,
                    'wallet' => [
                        'id' => $wallet->id,
                        'wallet_id' => $wallet->wallet_id,
                        'balance' => $wallet->balance,
                        'status' => $wallet->status,
                        'is_kyc_verified' => $wallet->is_kyc_verified,
                        'daily_limit' => $wallet->daily_limit,
                        'monthly_limit' => $wallet->monthly_limit,
                        'mobikwik_wallet_id' => $wallet->mobikwik_wallet_id
                    ]
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Get user data error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to load user data'
            ], 500);
        }
    }

    public function getTransactions(Request $request)
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated'
                ], 401);
            }

            $perPage = $request->get('per_page', 10);
            $transactions = WalletTransaction::where('user_id', $user->id,)
                                  ->orderBy('created_at', 'desc')
                                  ->paginate($perPage);

            return response()->json([
                'success' => true,
                'data' => $transactions
            ]);

        } catch (\Exception $e) {
            Log::error('Get transactions error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to load transactions'
            ], 500);
        }
    }

    public function getBalance()
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated'
                ], 401);
            }

            $wallet = $this->walletService->getOrCreateWallet($user);

            return response()->json([
                'success' => true,
                'data' => [
                    'balance' => $user->wallet_balance ?? 0,
                    'wallet_balance' => $wallet->balance,
                    'wallet_id' => $wallet->wallet_id,
                    'wallet_table_id' => $wallet->id,
                    'status' => $wallet->status
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Get balance error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to load balance'
            ], 500);
        }
    }

    public function processPayment(Request $request)
    {
        try {
            $request->validate([
                'amount' => 'required|numeric|min:1',
                'description' => 'nullable|string|max:255',
            ]);

            $user = Auth::user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated'
                ], 401);
            }

            $wallet = $this->walletService->getOrCreateWallet($user);

            $result = $this->mobikwikService->processPayment(
                $user,
                $wallet,
                $request->amount,
                $request->description
            );

            if ($result['success']) {
                return response()->json([
                    'success' => true,
                    'redirect_required' => $result['redirect_required'] ?? false,
                    'redirect_url' => $result['redirect_url'] ?? null,
                    'message' => $result['message'] ?? 'Payment processed successfully',
                    'data' => [
                        'transaction_id' => $result['transaction_id'] ?? null,
                        'wallet_id' => $result['wallet_id'] ?? null,
                        'payment_mode' => $result['payment_mode'] ?? null
                    ]
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => $result['message'] ?? 'Payment processing failed'
            ], 422);
// dd($wallet);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->validator->errors()
            ], 422);
            
        } catch (\Exception $e) {
            Log::error('Payment processing error: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'amount' => $request->amount ?? null,
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Payment processing failed. Please try again.'
            ], 500);
        }
    }

    public function addMoney(Request $request)
    {
        try {
            $request->validate([
                'amount' => 'required|numeric|min:10|max:50000',
            ]);

            $user = Auth::user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated'
                ], 401);
            }

            $result = $this->mobikwikService->addMoneyToWallet($user, $request->amount);

            if ($result['success']) {
                return response()->json([
                    'success' => true,
                    'redirect_url' => $result['redirect_url'],
                    'message' => 'Redirecting to payment gateway...',
                                        'data' => [
                        'transaction_id' => $result['transaction_id'] ?? null
                    ]
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => $result['message'] ?? 'Failed to add money to wallet'
            ], 422);
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->validator->errors()
            ], 422);
            
        } catch (\Exception $e) {
            Log::error('Add money error: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'amount' => $request->amount ?? null,
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to add money to wallet. Please try again.'
            ], 500);
        }
    }

    public function callback(Request $request)
    {
        try {
            $responseData = $request->all();
            
            Log::info('Wallet topup callback received', $responseData);
            
            $result = $this->mobikwikService->handleWalletTopupCallback($responseData);

            if ($result['success']) {
                return redirect()->route('wallet')->with('success', 'Money added successfully!');
            }

            return redirect()->route('wallet')->with('error', $result['message'] ?? 'Payment failed!');
            
        } catch (\Exception $e) {
            Log::error('Wallet callback error: ' . $e->getMessage(), [
                'callback_data' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('wallet')->with('error', 'Payment processing failed!');
        }
    }

    public function paymentCallback(Request $request)
    {
        try {
            $responseData = $request->all();
            
            Log::info('Payment callback received', $responseData);
            
            $result = $this->mobikwikService->handleCallback($responseData);

            if ($result['success']) {
                return redirect()->route('payment.success')->with('success', 'Payment completed successfully!');
            }

            return redirect()->route('payment.failed')->with('error', $result['message'] ?? 'Payment failed!');
            
        } catch (\Exception $e) {
            Log::error('Payment callback error: ' . $e->getMessage(), [
                'callback_data' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('payment.failed')->with('error', 'Payment processing failed!');
        }
    }

    public function cancel(Request $request)
    {
        Log::info('Payment cancelled', [
            'user_id' => Auth::id(),
            'request_data' => $request->all()
        ]);
        
        return redirect()->route('wallet')->with('error', 'Payment was cancelled!');
    }
}

