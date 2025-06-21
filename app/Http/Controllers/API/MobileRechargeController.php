<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MobileRecharge;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class MobileRechargeController extends Controller
{
    // POST /api/recharge/submit
    public function submit(Request $request)
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated'
                ], 401);
            }

            $validator = Validator::make($request->all(), [
                'mobile_no' => 'required|digits:10',
                'operator'  => 'required|string|in:airtel,jio,vi,bsnl',
                'circle'    => 'required|string|in:prepaid,postpaid,talktime,validity',
                'amount'    => 'required|numeric|min:1|max:10000',
            ], [
                'mobile_no.required' => 'Mobile number is required',
                'mobile_no.digits' => 'Please enter a valid 10-digit mobile number',
                'operator.required' => 'Please select an operator',
                'operator.in' => 'Please select a valid operator',
                'circle.required' => 'Please select a plan type',
                'circle.in' => 'Please select a valid plan type',
                'amount.required' => 'Amount is required',
                'amount.numeric' => 'Amount must be a number',
                'amount.min' => 'Minimum amount is ₹1',
                'amount.max' => 'Maximum amount is ₹10,000'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first(),
                    'errors' => $validator->errors()
                ], 422);
            }

            // Check wallet balance
            $rechargeAmount = floatval($request->amount);
            $userBalance = floatval($user->wallet_balance ?? 0);

            if ($userBalance < $rechargeAmount) {
                return response()->json([
                    'success' => false,
                    'message' => 'Insufficient wallet balance. Please add money to your wallet.',
                    'data' => [
                        'required_amount' => $rechargeAmount,
                        'current_balance' => $userBalance,
                        'shortfall' => $rechargeAmount - $userBalance
                    ]
                ], 422);
            }

            // Generate unique transaction ID
            $txn_id = 'MR' . date('Ymd') . strtoupper(Str::random(4)) . rand(1000, 9999);

            // Deduct balance first
            if (!$this->deductUserBalance($user, $rechargeAmount)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to deduct balance from wallet'
                ], 500);
            }

            // Create mobile recharge record
            $recharge = MobileRecharge::create([
                'user_id'   => $user->id,
                'mobile_no' => $request->mobile_no,
                'operator'  => $request->operator,
                'circle'    => $request->circle,
                'amount'    => $rechargeAmount,
                'txn_id'    => $txn_id,
                'status'    => 'Pending',
            ]);

            // Process recharge
            $this->processRecharge($recharge);
            $recharge = $recharge->fresh();

            // If recharge failed, refund the amount
            if ($recharge->status === 'Failed') {
                $this->refundUserBalance($user, $rechargeAmount);
                
                return response()->json([
                    'success' => false,
                    'message' => 'Recharge failed. Amount has been refunded to your wallet.',
                    'data' => $recharge
                ], 200);
            } else {
                return response()->json([
                    'success' => true,
                    'message' => 'Recharge successful!',
                    'data' => $recharge
                ]);
            }

        } catch (\Exception $e) {
            // Refund amount if something goes wrong
            if (isset($user) && isset($rechargeAmount)) {
                $this->refundUserBalance($user, $rechargeAmount);
            }
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to process mobile recharge',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // GET /api/recharge/history
    public function history()
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated'
                ], 401);
            }

            $history = MobileRecharge::where('user_id', $user->id)
                                   ->orderBy('created_at', 'desc')
                                   ->limit(20)
                                   ->get();

            return response()->json($history);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch recharge history',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // GET /api/recharge/search
    public function search(Request $request)
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated'
                ], 401);
            }

            $query = MobileRecharge::where('user_id', $user->id);

            // Date range filtering
            if ($request->has('from_date') && $request->has('to_date')) {
                $fromDate = $request->from_date . ' 00:00:00';
                $toDate = $request->to_date . ' 23:59:59';
                $query->whereBetween('created_at', [$fromDate, $toDate]);
            }

            // Status filtering
            if ($request->has('status') && $request->status !== '') {
                $query->where('status', $request->status);
            }

            // Mobile number filtering
            if ($request->has('mobile_no') && $request->mobile_no !== '') {
                $query->where('mobile_no', $request->mobile_no);
            }

            // Transaction ID filtering
            if ($request->has('txn_id') && $request->txn_id !== '') {
                $query->where('txn_id', $request->txn_id);
            }

            // Operator filtering
            if ($request->has('operator') && $request->operator !== '') {
                $query->where('operator', $request->operator);
            }

            $perPage = $request->get('per_page', 50);
            $recharges = $query->orderBy('created_at', 'desc')->paginate($perPage);

            return response()->json([
                'success' => true,
                'data' => $recharges
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to search recharges',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // GET /api/recharge/statistics
    public function statistics()
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated'
                ], 401);
            }

            $stats = [
                'total_recharges' => MobileRecharge::where('user_id', $user->id)->count(),
                'pending_recharges' => MobileRecharge::where('user_id', $user->id)->where('status', 'Pending')->count(),
                'successful_recharges' => MobileRecharge::where('user_id', $user->id)->where('status', 'Success')->count(),
                'failed_recharges' => MobileRecharge::where('user_id', $user->id)->where('status', 'Failed')->count(),
                'total_amount' => MobileRecharge::where('user_id', $user->id)->where('status', 'Success')->sum('amount'),
                'today_recharges' => MobileRecharge::where('user_id', $user->id)->whereDate('created_at', today())->count(),
                'today_amount' => MobileRecharge::where('user_id', $user->id)->whereDate('created_at', today())
                                ->where('status', 'Success')
                                ->sum('amount')
            ];

            return response()->json([
                'success' => true,
                'data' => $stats
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch statistics',
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

    private function processRecharge(MobileRecharge $recharge): void
    {
        try {
            // Simulate recharge processing (80% success rate for demo)
            $success = rand(1, 10) > 2;
            
            if ($success) {
                $recharge->update(['status' => 'Success']);
            } else {
                $recharge->update(['status' => 'Failed']);
            }
            
        } catch (\Exception $e) {
            $recharge->update(['status' => 'Failed']);
        }
    }
}
