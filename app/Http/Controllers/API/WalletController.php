<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;

use App\Models\WalletTransaction;
use App\Services\MobiKwikService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class WalletController extends Controller
{
    private MobiKwikService $mobikwikService;

    public function __construct(MobiKwikService $mobikwikService)
    {
        $this->mobikwikService = $mobikwikService;
    }

    public function index()
    {
        $user = Auth::user();
        $transactions = WalletTransaction::where('user_id', $user->id)
                              ->orderBy('created_at', 'desc')
                              ->paginate(10);


        return view('wallet.index', compact('user', 'transactions'));
    }

    public function processPayment(Request $request)
    {
        try {
            // Validate the request
            $request->validate([
                'amount' => 'required|numeric|min:1',
                'description' => 'nullable|string|max:255',
            ]);

            $user = Auth::user();
            
            // Check if user is authenticated
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated'
                ], 401);
            }

            // Process payment through MobiKwik service
            $result = $this->mobikwikService->processPayment(
                $user,
                $request->amount,
                $request->description
            );

            if ($result['success']) {
                if ($result['redirect_required']) {
                    return response()->json([
                        'success' => true,
                        'redirect_url' => $result['redirect_url'],
                        'message' => $result['message'] ?? 'Redirecting to payment gateway...'
                    ]);
                } else {
                    return response()->json([
                        'success' => true,
                        'message' => $result['message'] ?? 'Payment processed successfully'
                    ]);
                }
            }

            return response()->json([
                'success' => false,
                'message' => $result['message'] ?? 'Payment processing failed'
            ], 422);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed: ' . implode(', ', $e->validator->errors()->all())
            ], 422);
            
        } catch (\Exception $e) {
            // Log the error for debugging
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
                return back()->with('error', 'User not authenticated');
            }

            $result = $this->mobikwikService->addMoneyToWallet($user, $request->amount);

            if ($result['success']) {
                return redirect($result['redirect_url']);
            }

            return back()->with('error', $result['message'] ?? 'Failed to add money to wallet');
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->with('error', 'Validation failed: ' . implode(', ', $e->validator->errors()->all()));
            
        } catch (\Exception $e) {
            Log::error('Add money error: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'amount' => $request->amount ?? null,
                'trace' => $e->getTraceAsString()
            ]);

            return back()->with('error', 'Failed to add money to wallet. Please try again.');
        }
    }

    public function callback(Request $request)
    {
        try {
            $responseData = $request->all();
            
            // Log callback data for debugging
            Log::info('Wallet topup callback received', $responseData);
            
            $result = $this->mobikwikService->handleWalletTopupCallback($responseData);

            if ($result['success']) {
                return redirect()->route('wallet.index')->with('success', 'Money added successfully!');
            }

            return redirect()->route('wallet.index')->with('error', $result['message'] ?? 'Payment failed!');
            
        } catch (\Exception $e) {
            Log::error('Wallet callback error: ' . $e->getMessage(), [
                'callback_data' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('wallet.index')->with('error', 'Payment processing failed!');
        }
    }

    public function paymentCallback(Request $request)
    {
        try {
            $responseData = $request->all();
            
            // Log callback data for debugging
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
        
        return redirect()->route('wallet.index')->with('error', 'Payment was cancelled!');
    }
}
