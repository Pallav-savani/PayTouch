<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\CcBillPayment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class CcBillPaymentController extends Controller
{
    /**
     * Display a listing of CC bill payments
     */
    public function index(Request $request)
    {
        try {
            $query = CcBillPayment::with('user:id,email');

            // Handle fetch bill request (search by CC number and mobile)
            if ($request->has('cn') && $request->has('mobile')) {
                return $this->fetchBillsByCardAndMobile($request);
            }

            // If user is authenticated, show only their payments (unless admin)
            if (Auth::check() && !Auth::user()->is_admin) {
                $query->where('user_id', Auth::id());
            }

            // Filter by user ID (admin only)
            if ($request->has('user_id') && !empty($request->user_id)) {
                if (!Auth::check() || Auth::user()->is_admin) {
                    $query->where('user_id', $request->user_id);
                }
            }

            // Filter by uid
            if ($request->has('uid') && !empty($request->uid)) {
                $query->where('uid', $request->uid);
            }

            // Filter by status
            if ($request->has('status') && !empty($request->status)) {
                $query->where('status', $request->status);
            }

            // Filter by operator
            if ($request->has('op') && !empty($request->op)) {
                $query->where('op', $request->op);
            }

            // Filter by date range
            if ($request->has('start_date') && $request->has('end_date')) {
                $query->whereBetween('created_at', [
                    Carbon::parse($request->start_date)->startOfDay(),
                    Carbon::parse($request->end_date)->endOfDay()
                ]);
            }

            // Search functionality
            if ($request->has('search') && !empty($request->search)) {
                $searchTerm = $request->search;
                $query->where(function($q) use ($searchTerm) {
                    $q->where('reqid', 'LIKE', "%{$searchTerm}%")
                      ->orWhere('transaction_id', 'LIKE', "%{$searchTerm}%")
                      ->orWhere('operator_ref', 'LIKE', "%{$searchTerm}%")
                      ->orWhere('op', 'LIKE', "%{$searchTerm}%")
                      ->orWhere('ad9', 'LIKE', "%{$searchTerm}%")
                      ->orWhere('ad3', 'LIKE', "%{$searchTerm}%");
                });
            }

            // Get paginated results
            $perPage = $request->get('per_page', 15);
            $ccBillPayments = $query->orderBy('created_at', 'desc')
                                   ->paginate($perPage);

            // Decrypt credit card numbers for display
            $items = $ccBillPayments->items();
            foreach ($items as $item) {
                if ($item->cn) {
                    try {
                        $decrypted = decrypt($item->cn);
                        $item->cn = $decrypted;
                    } catch (\Exception $e) {
                        // If decryption fails, keep original value
                        Log::warning('Failed to decrypt credit card number for record ID: ' . $item->id);
                    }
                }
            }

            return response()->json([
                'status' => 'success',
                'message' => 'CC bill payments retrieved successfully',
                'data' => $items,
                'pagination' => [
                    'current_page' => $ccBillPayments->currentPage(),
                    'last_page' => $ccBillPayments->lastPage(),
                    'per_page' => $ccBillPayments->perPage(),
                    'total' => $ccBillPayments->total(),
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('CC Bill Payment Index Error: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch CC bill payments',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Fetch bills by credit card number and mobile number
     */
    private function fetchBillsByCardAndMobile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'cc_number' => 'required|string|min:10',
            'mobile_number' => 'required|string|size:10',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $ccNumber = $request->cc_number;
            $mobileNumber = $request->mobile_number;
            $lastFourDigits = substr($ccNumber, -4);

            Log::info('Searching for bills with CC ending: ' . $lastFourDigits . ' and mobile: ' . $mobileNumber);

            // Get all records first, then filter by decrypted values
            $allRecords = CcBillPayment::with('user:id,name,email')
                ->where(function($query) use ($mobileNumber) {
                    $query->where('ad9', $mobileNumber)
                          ->orWhere('ad3', $mobileNumber);
                })
                ->get();

            $matchingBills = collect();

            foreach ($allRecords as $record) {
                try {
                    // Decrypt the credit card number
                    $decryptedCC = decrypt($record->cn);
                    
                    // Check if the decrypted CC number matches
                    if (substr($decryptedCC, -4) === $lastFourDigits || $decryptedCC === $ccNumber) {
                        // Set the decrypted value for display
                        $record->cn = $decryptedCC;
                        $matchingBills->push($record);
                    }
                } catch (\Exception $e) {
                    // If decryption fails, try direct comparison with last 4 digits
                    if (str_contains($record->cn, $lastFourDigits)) {
                        $matchingBills->push($record);
                    }
                    Log::warning('Decryption failed for record ID: ' . $record->id . ' - ' . $e->getMessage());
                }
            }

            Log::info('Found ' . $matchingBills->count() . ' matching bills');

            return response()->json([
                'status' => 'success',
                'message' => 'Bills fetched successfully',
                'data' => $matchingBills->values()->toArray()
            ]);

        } catch (\Exception $e) {
            Log::error('CC Bill Fetch Error: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch bills',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Store a new CC bill payment
     */
    public function store(Request $request)
    {
        try {
            // Handle fetch bill request
            if ($request->has('cn') && $request->has('mobile')) {
                return $this->fetchBillsByCardAndMobile($request);
            }

            $validator = Validator::make($request->all(), [
                'user_id' => 'required|exists:users,id',
                'cn' => 'required|string|max:20|min:13',
                'op' => 'required|string|max:50',
                'cir' => 'required|string|max:50',
                'amt' => 'required|numeric|min:1|max:60000',
                'ad9' => 'nullable|string|max:255',
                'ad3' => 'nullable|string|max:255',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Get user details from users table
            $user = User::findOrFail($request->user_id);

            // Check if authenticated user can make payment for this user
            if (Auth::check() && !Auth::user()->is_admin && Auth::id() !== $user->id) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthorized to make payment for this user'
                ], 403);
            }

            // Generate unique request ID
            $reqid = 'CC' . time() . rand(1000, 9999);

            // Ensure reqid is unique
            while (CcBillPayment::where('reqid', $reqid)->exists()) {
                $reqid = 'CC' . time() . rand(1000, 9999);
            }

            // Get uid and pwd from user
            $uid = $user->id;
            $pwd = $user->password;

            $ccBillPayment = CcBillPayment::create([
                'user_id' => $user->id,
                'uid' => $uid,
                'pwd' => $pwd,
                'cn' => encrypt($request->cn), // Encrypt credit card number
                'op' => $request->op,
                'cir' => $request->cir,
                'amt' => $request->amt,
                'reqid' => $reqid,
                'ad9' => $request->ad9,
                'ad3' => $request->ad3,
                'status' => 'pending'
            ]);

            // Process the payment
            $this->processPayment($ccBillPayment);

            // Decrypt credit card number before returning
            $decryptedCn = $ccBillPayment->cn;
            try {
                $decryptedCn = decrypt($ccBillPayment->cn);
            } catch (\Exception $e) {
                Log::warning('Failed to decrypt credit card number for record ID: ' . $ccBillPayment->id);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'CC bill payment initiated successfully',
                'data' => [
                    'id' => $ccBillPayment->id,
                    'cn' => $decryptedCn,
                    'reqid' => $ccBillPayment->reqid,
                    'status' => $ccBillPayment->status,
                    'amt' => $ccBillPayment->amt,
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email
                    ],
                    'created_at' => $ccBillPayment->created_at
                ]
            ], 201);

        } catch (\Exception $e) {
            Log::error('CC Bill Payment Store Error: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create CC bill payment',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Display the specified CC bill payment
     */
    public function show($id)
    {
        try {
            $ccBillPayment = CcBillPayment::with('user:id,email')->findOrFail($id);

            // Check if user can view this payment
            if (Auth::check() && !Auth::user()->is_admin && Auth::id() !== $ccBillPayment->user_id) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthorized to view this payment'
                ], 403);
            }

            // Decrypt credit card number for display
            if ($ccBillPayment->cn) {
                try {
                    $ccBillPayment->cn = decrypt($ccBillPayment->cn);
                } catch (\Exception $e) {
                    Log::warning('Failed to decrypt credit card number for record ID: ' . $ccBillPayment->id);
                }
            }

            return response()->json([
                'status' => 'success',
                'message' => 'CC bill payment retrieved successfully',
                'data' => $ccBillPayment
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'CC bill payment not found'
            ], 404);
        } catch (\Exception $e) {
            Log::error('CC Bill Payment Show Error: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch CC bill payment',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Update the specified CC bill payment
     */
    public function update(Request $request, $id)
    {
        try {
            $ccBillPayment = CcBillPayment::findOrFail($id);

            // Check if user can update this payment (admin only for status updates)
            if (Auth::check() && !Auth::user()->is_admin) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthorized to update payment status'
                ], 403);
            }

            $validator = Validator::make($request->all(), [
                'status' => 'sometimes|in:pending,success,failed',
                'transaction_id' => 'sometimes|string|max:255',
                'operator_ref' => 'sometimes|string|max:255',
                'response_message' => 'sometimes|string',
                'api_response' => 'sometimes|array',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $updateData = $request->only(['status', 'transaction_id', 'operator_ref', 'response_message', 'api_response']);
            
            if ($request->has('status') && $request->status !== 'pending') {
                $updateData['processed_at'] = Carbon::now();
            }

            $ccBillPayment->update($updateData);

            return response()->json([
                'status' => 'success',
                'message' => 'CC bill payment updated successfully',
                'data' => $ccBillPayment->fresh()->load('user:id,name,email')
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'CC bill payment not found'
            ], 404);

        } catch (\Exception $e) {
            Log::error('CC Bill Payment Update Error: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update CC bill payment',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Remove the specified CC bill payment
     */
    public function destroy($id)
    {
        try {
            $ccBillPayment = CcBillPayment::findOrFail($id);

            // Check if user can delete this payment (admin only)
            if (Auth::check() && !Auth::user()->is_admin) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthorized to delete payment'
                ], 403);
            }

            $ccBillPayment->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'CC bill payment deleted successfully'
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'CC bill payment not found'
            ], 404);

        } catch (\Exception $e) {
            Log::error('CC Bill Payment Delete Error: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete CC bill payment',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Get pending CC bill payments
     */
    public function getPendingPayments(Request $request)
    {
        try {
            $query = CcBillPayment::with('user:id,name,email')->byStatus('pending');

            // If user is not admin, show only their payments
            if (Auth::check() && !Auth::user()->is_admin) {
                $query->byUser(Auth::id());
            }

            $perPage = $request->get('per_page', 15);
            $pendingPayments = $query->orderBy('created_at', 'desc')
                                   ->paginate($perPage);

            // Decrypt credit card numbers for display
            $items = $pendingPayments->items();
            foreach ($items as $item) {
                if ($item->cn) {
                    try {
                        $item->cn = decrypt($item->cn);
                    } catch (\Exception $e) {
                        Log::warning('Failed to decrypt credit card number for record ID: ' . $item->id);
                    }
                }
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Pending CC bill payments retrieved successfully',
                'data' => $items,
                'pagination' => [
                    'current_page' => $pendingPayments->currentPage(),
                    'last_page' => $pendingPayments->lastPage(),
                    'per_page' => $pendingPayments->perPage(),
                    'total' => $pendingPayments->total(),
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('CC Bill Payment Pending Error: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch pending payments',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Get failed CC bill payments
     */
    public function getFailedPayments(Request $request)
    {
        try {
            $query = CcBillPayment::with('user:id,name,email')->byStatus('failed');

            // If user is not admin, show only their payments
            if (Auth::check() && !Auth::user()->is_admin) {
                $query->byUser(Auth::id());
            }

            $perPage = $request->get('per_page', 15);
            $failedPayments = $query->orderBy('created_at', 'desc')
                                  ->paginate($perPage);

            // Decrypt credit card numbers for display
            $items = $failedPayments->items();
            foreach ($items as $item) {
                if ($item->cn) {
                    try {
                        $item->cn = decrypt($item->cn);
                    } catch (\Exception $e) {
                        Log::warning('Failed to decrypt credit card number for record ID: ' . $item->id);
                    }
                }
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Failed CC bill payments retrieved successfully',
                'data' => $items,
                'pagination' => [
                    'current_page' => $failedPayments->currentPage(),
                    'last_page' => $failedPayments->lastPage(),
                    'per_page' => $failedPayments->perPage(),
                    'total' => $failedPayments->total(),
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('CC Bill Payment Failed Error: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch failed payments',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Get successful CC bill payments
     */
    public function getSuccessfulPayments(Request $request)
    {
        try {
            $query = CcBillPayment::with('user:id,name,email')->byStatus('success');

            // If user is not admin, show only their payments
            if (Auth::check() && !Auth::user()->is_admin) {
                $query->byUser(Auth::id());
            }

            $perPage = $request->get('per_page', 15);
            $successfulPayments = $query->orderBy('created_at', 'desc')
                                      ->paginate($perPage);

            // Decrypt credit card numbers for display
            $items = $successfulPayments->items();
            foreach ($items as $item) {
                if ($item->cn) {
                    try {
                        $item->cn = decrypt($item->cn);
                    } catch (\Exception $e) {
                        Log::warning('Failed to decrypt credit card number for record ID: ' . $item->id);
                    }
                }
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Successful CC bill payments retrieved successfully',
                'data' => $items,
                'pagination' => [
                    'current_page' => $successfulPayments->currentPage(),
                    'last_page' => $successfulPayments->lastPage(),
                    'per_page' => $successfulPayments->perPage(),
                    'total' => $successfulPayments->total(),
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('CC Bill Payment Success Error: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch successful payments',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Retry a failed payment
     */
    public function retryPayment($id)
    {
        try {
            $ccBillPayment = CcBillPayment::findOrFail($id);

            // Check if user can retry this payment
            if (Auth::check() && !Auth::user()->is_admin && Auth::id() !== $ccBillPayment->user_id) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthorized to retry this payment'
                ], 403);
            }

            if ($ccBillPayment->status !== 'failed') {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Only failed payments can be retried'
                ], 400);
            }

            // Reset payment status to pending
            $ccBillPayment->update([
                'status' => 'pending',
                'processed_at' => null,
                'response_message' => 'Payment retry initiated'
            ]);

            // Process the payment again
            $this->processPayment($ccBillPayment);

            return response()->json([
                'status' => 'success',
                'message' => 'Payment retry initiated successfully',
                'data' => $ccBillPayment->fresh()->load('user:id,name,email')
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'CC bill payment not found'
            ], 404);

        } catch (\Exception $e) {
            Log::error('CC Bill Payment Retry Error: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retry payment',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Get payment statistics
     */
    public function getStatistics(Request $request)
    {
        try {
            $query = CcBillPayment::query();

            // If user is not admin, show only their statistics
            if (Auth::check() && !Auth::user()->is_admin) {
                $query->byUser(Auth::id());
            }

            // Filter by date range if provided
            if ($request->has('start_date') && $request->has('end_date')) {
                $query->dateRange($request->start_date, $request->end_date);
            }

            $statistics = [
                'total_payments' => $query->count(),
                'successful_payments' => $query->clone()->byStatus('success')->count(),
                'pending_payments' => $query->clone()->byStatus('pending')->count(),
                'failed_payments' => $query->clone()->byStatus('failed')->count(),
                'total_amount' => $query->sum('amt'),
                'successful_amount' => $query->clone()->byStatus('success')->sum('amt'),
                'pending_amount' => $query->clone()->byStatus('pending')->sum('amt'),
                'failed_amount' => $query->clone()->byStatus('failed')->sum('amt'),
            ];

            // Calculate success rate
            $statistics['success_rate'] = $statistics['total_payments'] > 0 
                ? round(($statistics['successful_payments'] / $statistics['total_payments']) * 100, 2) 
                : 0;

            return response()->json([
                'status' => 'success',
                'message' => 'CC bill payment statistics retrieved successfully',
                'data' => $statistics
            ]);

        } catch (\Exception $e) {
            Log::error('CC Bill Payment Statistics Error: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch statistics',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Get user's payment history
     */
    public function getUserPayments(Request $request, $userId)
    {
        try {
            // Check if user can view this user's payments
            if (Auth::check() && !Auth::user()->is_admin && Auth::id() != $userId) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Unauthorized to view this user\'s payments'
                ], 403);
            }

            $user = User::findOrFail($userId);
            $query = CcBillPayment::with('user:id,name,email')->byUser($userId);

            // Filter by status if provided
            if ($request->has('status') && !empty($request->status)) {
                $query->byStatus($request->status);
            }

            // Filter by date range if provided
            if ($request->has('start_date') && $request->has('end_date')) {
                $query->dateRange($request->start_date, $request->end_date);
            }

            $perPage = $request->get('per_page', 15);
            $payments = $query->orderBy('created_at', 'desc')
                            ->paginate($perPage);

            // Decrypt credit card numbers for display
            $items = $payments->items();
            foreach ($items as $item) {
                if ($item->cn) {
                    try {
                        $item->cn = decrypt($item->cn);
                    } catch (\Exception $e) {
                        Log::warning('Failed to decrypt credit card number for record ID: ' . $item->id);
                    }
                }
            }

            return response()->json([
                'status' => 'success',
                'message' => 'User CC bill payments retrieved successfully',
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email
                ],
                'data' => $items,
                'pagination' => [
                    'current_page' => $payments->currentPage(),
                    'last_page' => $payments->lastPage(),
                    'per_page' => $payments->perPage(),
                ]]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'User not found'
            ], 404);

        } catch (\Exception $e) {
            Log::error('CC Bill Payment User History Error: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch user payments',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }

    /**
     * Process the CC bill payment (integrate with payment gateway)
     */
    private function processPayment(CcBillPayment $ccBillPayment)
    {
        try {
            // This is where you would integrate with your actual payment gateway
            // For now, we'll simulate the payment process
            
            Log::info('Processing CC Bill Payment', [
                'reqid' => $ccBillPayment->reqid,
                'amount' => $ccBillPayment->amt,
                'operator' => $ccBillPayment->op
            ]);

            // Simulate payment processing with random success/failure
            // In real implementation, you would call your payment gateway API here
            $success = rand(1, 10) > 2; // 80% success rate for simulation

            if ($success) {
                $ccBillPayment->update([
                    'status' => 'success',
                    'transaction_id' => 'TXN' . time() . rand(1000, 9999),
                    'operator_ref' => 'OP' . time() . rand(100, 999),
                    'response_message' => 'Payment processed successfully',
                    'api_response' => [
                        'status' => 'success',
                        'message' => 'CC bill payment successful',
                        'timestamp' => now()->toISOString()
                    ],
                    'processed_at' => Carbon::now()
                ]);
            } else {
                $ccBillPayment->update([
                    'status' => 'failed',
                    'response_message' => 'Payment failed due to insufficient funds or invalid card details',
                    'api_response' => [
                        'status' => 'failed',
                        'error_code' => 'PAYMENT_FAILED',
                        'message' => 'Payment could not be processed',
                        'timestamp' => now()->toISOString()
                    ],
                    'processed_at' => Carbon::now()
                ]);
            }

        } catch (\Exception $e) {
            Log::error('CC Bill Payment Processing Error: ' . $e->getMessage());
            
            $ccBillPayment->update([
                'status' => 'failed',
                'response_message' => 'Payment processing failed due to system error',
                'api_response' => [
                    'status' => 'error',
                    'error_code' => 'SYSTEM_ERROR',
                    'message' => 'System error occurred during payment processing',
                    'timestamp' => now()->toISOString()
                ],
                'processed_at' => Carbon::now()
            ]);
        }
    }
}
