<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Dth;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Wallet;
use App\Services\MobiKwikService;
use Illuminate\Support\Facades\Auth;
use \Illuminate\Support\Facades\Log;

class DthController extends Controller
{
    /**
     * Display a listing of DTH recharges for authenticated user
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User not authenticated'
                ], 401);
            }

            $query = Dth::where('user_id', $user->id)->orderBy('created_at', 'desc');
            
            // Date range filtering
            if ($request->has('from_date') && $request->has('to_date')) {
                $fromDate = $request->from_date . ' 00:00:00';
                $toDate = $request->to_date . ' 23:59:59';
                $query->whereBetween('created_at', [$fromDate, $toDate]);
            }
            
            // Status filtering - only apply if status is provided and not empty
            if ($request->has('status') && $request->status !== '' && $request->status !== null) {
                $query->where('status', $request->status);
            }
            
            // Service filtering - only apply if service is provided and not empty
            if ($request->has('service') && $request->service !== '' && $request->service !== null) {
                $query->where('service', $request->service);
            }
            
            // Mobile number filtering
            if ($request->has('mobile_no') && $request->mobile_no !== '') {
                $query->where('mobile_no', $request->mobile_no);
            }

            if ($request->has('transaction_id') && $request->transaction_id !== '') {
                $query->where('transaction_id', $request->transaction_id);
            }

            $perPage = $request->get('per_page', 50);
            $recharges = $query->paginate($perPage);
            
            return response()->json([
                'status' => 'success',
                'message' => 'DTH recharges retrieved successfully',
                'data' => $recharges
            ], 200);
            
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch DTH recharges',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created DTH recharge
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User not authenticated'
                ], 401);
            }

            // Validation rules
            $validator = Validator::make($request->all(), [
                'service' => 'required|string|in:airtel,bigtv,dishtv,tatasky,videocon,suntv',
                'mobile_no' => 'required|string|regex:/^[0-9]{10}$/',
                'amount' => 'required|numeric|min:1|max:10000'
            ], [
                'service.required' => 'Please select an operator',
                'service.in' => 'Please select a valid operator',
                'mobile_no.required' => 'Mobile number is required',
                'mobile_no.regex' => 'Please enter a valid 10-digit mobile number',
                'amount.required' => 'Amount is required',
                'amount.numeric' => 'Amount must be a number',
                'amount.min' => 'Minimum amount is ₹1',
                'amount.max' => 'Maximum amount is ₹10,000'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Check wallet balance
            $rechargeAmount = floatval($request->amount);
            $userBalance = floatval($user->wallet_balance ?? 0);

            if ($userBalance < $rechargeAmount) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Insufficient wallet balance. Please add money to your wallet.',
                    'data' => [
                        'required_amount' => $rechargeAmount,
                        'current_balance' => $userBalance,
                        'shortfall' => $rechargeAmount - $userBalance
                    ]
                ], 422);
            }

            // Generate unique transaction ID
            $transactionId = 'PYTCH' . date('Ymd') . $this->generateRandomString(4) . rand(1000, 9999);

            // Deduct balance first
            if (!$this->deductUserBalance($user, $rechargeAmount)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Failed to deduct balance from wallet'
                ], 500);
            }

            // Create DTH recharge record
            $dthRecharge = Dth::create([
                'user_id' => $user->id,
                'service' => $request->service,
                'mobile_no' => $request->mobile_no,
                'amount' => $rechargeAmount,
                'transaction_id' => $transactionId,
                'status' => 'pending'
            ]);

            // Process recharge
            $this->processRecharge($dthRecharge);
            $dthRecharge = $dthRecharge->fresh();

            // If recharge failed, refund the amount
            if ($dthRecharge->status === 'failed') {
                $this->refundUserBalance($user, $rechargeAmount);

                Log::info('DTH Recharge API', [
                    'status' => $request->all(),
                    'message' => 'DTH Recharge API',
                    'data' => $dthRecharge
                ]);
                
                return response()->json([
                    'status' => 'failed',
                    'message' => 'Recharge failed. Amount has been refunded to your wallet.',
                    'data' => $dthRecharge
                ], 200);
            } else {

                Log::info('DTH Recharge API', [
                    'status' => $request->all(),
                    'message' => 'DTH Recharge API',
                    'data' => $dthRecharge
                ]);

                return response()->json([
                    'status' => 'success',
                    'message' => 'DTH recharge initiated successfully',
                    'data' => $dthRecharge
                ], 201);
            }

        } catch (\Exception $e) {
            // Refund amount if something goes wrong
            if (isset($user) && isset($rechargeAmount)) {
                $this->refundUserBalance($user, $rechargeAmount);
            }
            
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to process DTH recharge',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    private function deductUserBalance(User $user, float $amount): bool
    {
        if (($user->wallet_balance ?? 0) >= $amount) {
            $user->wallet_balance = ($user->wallet_balance ?? 0) - $amount;
            return $user->save();
        }
        return false;
    }

    private function refundUserBalance(User $user, float $amount): bool
    {
        $user->wallet_balance = ($user->wallet_balance ?? 0) + $amount;
        return $user->save();
    }

    private function generateRandomString($length = 4): string
    {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
        $randomString = '';

        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }

        return $randomString;
    } 

    /**
     * Display the specified DTH recharge
     */
    public function show($id): JsonResponse
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User not authenticated'
                ], 401);
            }

            $dthRecharge = Dth::where('user_id', $user->id)->findOrFail($id);

            Log::info('Transaction Details retrived successfully', [
                'status' => $id->all(),
                'message' => 'Transaction Details',
                'data' => $dthRecharge
            ]);
            
            return response()->json([
                'status' => 'success',
                'message' => 'DTH recharge retrieved successfully',
                'data' => $dthRecharge
                            ], 200);
            
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'DTH recharge not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch DTH recharge',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified DTH recharge
     */
    public function update(Request $request, $id): JsonResponse
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User not authenticated'
                ], 401);
            }

            $dthRecharge = Dth::where('user_id', $user->id)->findOrFail($id);
            
            // Validation rules for update
            $validator = Validator::make($request->all(), [
                'service' => 'sometimes|string|in:airtel,bigtv,dishtv,tatasky,videocon,suntv',
                'mobile_no' => 'sometimes|string|regex:/^[0-9]{10}$/',
                'amount' => 'sometimes|numeric|min:1|max:10000',
                'status' => 'sometimes|in:pending,completed,failed',
                'transaction_id' => 'sometimes|string'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $dthRecharge->update($request->only([
                'service', 'mobile_no', 'amount', 'status', 'transaction_id'
            ]));

            return response()->json([
                'status' => 'success',
                'message' => 'DTH recharge updated successfully',
                'data' => $dthRecharge->fresh()
            ], 200);
            
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'DTH recharge not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update DTH recharge',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified DTH recharge
     */
    public function destroy($id): JsonResponse
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User not authenticated'
                ], 401);
            }

            $dthRecharge = Dth::where('user_id', $user->id)->findOrFail($id);
            $dthRecharge->delete();
            
            return response()->json([
                'status' => 'success',
                'message' => 'DTH recharge deleted successfully'
            ], 200);
            
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'DTH recharge not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete DTH recharge',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get recharge statistics for authenticated user
     */
    public function statistics(): JsonResponse
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User not authenticated'
                ], 401);
            }

            $stats = [
                'total_recharges' => Dth::where('user_id', $user->id)->count(),
                'pending_recharges' => Dth::where('user_id', $user->id)->where('status', 'pending')->count(),
                'completed_recharges' => Dth::where('user_id', $user->id)->where('status', 'completed')->count(),
                'failed_recharges' => Dth::where('user_id', $user->id)->where('status', 'failed')->count(),
                'total_amount' => Dth::where('user_id', $user->id)->where('status', 'completed')->sum('amount'),
                'today_recharges' => Dth::where('user_id', $user->id)->whereDate('created_at', today())->count(),
                'today_amount' => Dth::where('user_id', $user->id)->whereDate('created_at', today())
                                    ->where('status', 'completed')
                                    ->sum('amount')
            ];

            return response()->json([
                'status' => 'success',
                'message' => 'Statistics retrieved successfully',
                'data' => $stats
            ], 200);
            
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch statistics',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    private function processRecharge(Dth $dthRecharge): void
    {
        try {
            $success = rand(1, 10) > 2; // 80% success rate for demo
            
            if ($success) {
                $dthRecharge->update([
                    'status' => 'success'
                ]);
            } else {
                $dthRecharge->update([
                    'status' => 'failed'
                ]);
            }
            
        } catch (\Exception $e) {
            $dthRecharge->update([
                'status' => 'failed'
            ]);
        }
    }

    // pending recharge code.
    public function getPendingTransactions(Request $request): JsonResponse
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User not authenticated'
                ], 401);
            }

            $query = Dth::where('user_id', $user->id)->where('status', 'pending')->orderBy('created_at', 'desc');
            
            // Date range filtering
            if ($request->has('from_date') && $request->has('to_date')) {
                $fromDate = $request->from_date . ' 00:00:00';
                $toDate = $request->to_date . ' 23:59:59';
                $query->whereBetween('created_at', [$fromDate, $toDate]);
            }
            
            // Mobile number filtering
            if ($request->has('mobile_no') && $request->mobile_no !== '') {
                $query->where('mobile_no', $request->mobile_no);
            }
            
            // Pagination
            $perPage = $request->get('per_page', 20);
            $pendingTransactions = $query->paginate($perPage);

            Log::info('Pending Transaction Details retrived successfully', [
                'status' => $request->all(),
                'message' => 'Pending Transaction Details',
                'data' => $query
            ]);
            
            return response()->json([
                'status' => 'success',
                'message' => 'Pending transactions retrieved successfully',
                'data' => $pendingTransactions,
                'count' => $pendingTransactions->total()
            ], 200);
            
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch pending transactions',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function retryTransaction($id): JsonResponse
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User not authenticated'
                ], 401);
            }

            $dthRecharge = Dth::where('user_id', $user->id)->findOrFail($id);
            
            if ($dthRecharge->status !== 'pending') {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Only pending transactions can be retried'
                ], 400);
            }
            
            // Process the recharge again
            $this->processRecharge($dthRecharge);
            $dthRecharge = $dthRecharge->fresh();

            Log::info('Pending Transaction Details retrived successfully', [
                'status' => $id->all(),
                'message' => 'Pending Transaction Details',
                'data' => $dthRecharge
            ]);
            
            return response()->json([
                'status' => 'success',
                'message' => 'Transaction retry completed',
                'data' => $dthRecharge
            ], 200);
            
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Transaction not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retry transaction',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function retryAllPending(): JsonResponse
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User not authenticated'
                ], 401);
            }

            $pendingTransactions = Dth::where('user_id', $user->id)->where('status', 'pending')->get();
            
            if ($pendingTransactions->isEmpty()) {
                return response()->json([
                    'status' => 'info',
                    'message' => 'No pending transactions found'
                ], 200);
            }
            
            $retryCount = 0;
            $successCount = 0;
            
            foreach ($pendingTransactions as $transaction) {
                $this->processRecharge($transaction);
                $retryCount++;
                
                $transaction = $transaction->fresh();
                if ($transaction->status === 'success') {
                    $successCount++;
                }
            }

            Log::info('Retry All Transaction Details', [
                'status' => $transaction->all(),
                'message' => 'Retry All Transaction Details',
                'data' => $pendingTransactions
            ]);
            
            return response()->json([
                'status' => 'success',
                'message' => "Retried {$retryCount} transactions. {$successCount} succeeded.",
                'data' => [
                    'retried' => $retryCount,
                    'succeeded' => $successCount,
                    'failed' => $retryCount - $successCount
                ]
            ], 200);
            
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retry pending transactions',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // failed recharge code
    public function getFailedTransactions(Request $request): JsonResponse
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User not authenticated'
                ], 401);
            }

            $query = Dth::where('user_id', $user->id)->where('status', 'failed')->orderBy('created_at', 'desc');
            
            // Date range filtering
            if ($request->has('from_date') && $request->has('to_date')) {
                $fromDate = $request->from_date . ' 00:00:00';
                $toDate = $request->to_date . ' 23:59:59';
                $query->whereBetween('created_at', [$fromDate, $toDate]);
            }
            
            // Mobile number filtering
            if ($request->has('mobile_no') && $request->mobile_no !== '') {
                $query->where('mobile_no', $request->mobile_no);
            }
            
            // Pagination
            $perPage = $request->get('per_page', 20);
            $failedTransactions = $query->paginate($perPage);

            Log::info('Retry All Transaction Details', [
                'status' => $request->all(),
                'message' => 'Retry All Transaction Details',
                'data' => $failedTransactions
            ]);
            
            return response()->json([
                'status' => 'success',
                'message' => 'Failed transactions retrieved successfully',
                'data' => $failedTransactions,
                'count' => $failedTransactions->total()
            ], 200);
            
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch failed transactions',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function retryFailedTransaction($id): JsonResponse
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User not authenticated'
                ], 401);
            }

            $dthRecharge = Dth::where('user_id', $user->id)->findOrFail($id);
            
            if ($dthRecharge->status !== 'failed') {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Only failed transactions can be retried'
                ], 400);
            }
            
            // Reset status to pending before retry
            $dthRecharge->update(['status' => 'pending']);
            
            // Process the recharge again
            $this->processRecharge($dthRecharge);
            $dthRecharge = $dthRecharge->fresh();

            Log::info('Retry Failed Transaction Details', [
                'status' => $dthRecharge->all(),
                'message' => 'Retry Failed Transaction Details',
                'data' => $dthRecharge
            ]);
            
            return response()->json([
                'status' => 'success',
                'message' => 'Failed transaction retry completed',
                'data' => $dthRecharge
            ], 200);
            
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Transaction not found'
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retry transaction',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function retryAllFailed(): JsonResponse
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User not authenticated'
                ], 401);
            }

            $failedTransactions = Dth::where('user_id', $user->id)->where('status', 'failed')->get();
            
            if ($failedTransactions->isEmpty()) {
                return response()->json([
                    'status' => 'info',
                    'message' => 'No failed transactions found'
                ], 200);
            }
            
            $retryCount = 0;
            $successCount = 0;
            
            foreach ($failedTransactions as $transaction) {
                // Reset to pending before retry
                $transaction->update(['status' => 'pending']);
                $this->processRecharge($transaction);
                $retryCount++;
                
                $transaction = $transaction->fresh();
                if ($transaction->status === 'success') {
                    $successCount++;
                }
            }

            Log::info('Retry All Transaction Details', [
                'status' => $transaction->all(),
                'message' => 'Retry All Transaction Details',
                'data' => $transaction
            ]);
            
            return response()->json([
                'status' => 'success',
                'message' => "Retried {$retryCount} failed transactions. {$successCount} succeeded.",
                'data' => [
                    'retried' => $retryCount,
                    'succeeded' => $successCount,
                    'failed' => $retryCount - $successCount
                ]
            ], 200);
            
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retry failed transactions',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}

