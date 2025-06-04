<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Dth;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class DthController extends Controller
{
    /**
     * Display a listing of DTH recharges
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $query = Dth::orderBy('created_at', 'desc');
            
            // Optional filtering
            if ($request->has('status')) {
                $query->where('status', $request->status);
            }
            
            if ($request->has('service')) {
                $query->where('service', $request->service);
            }
            
            if ($request->has('mobile_no')) {
                $query->where('mobile_no', $request->mobile_no);
            }
            
            // Pagination
            $perPage = $request->get('per_page', 10);
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

            // Generate unique transaction ID
            $transactionId = 'DTH' . time() . rand(1000, 9999);

            // Create DTH recharge record
            $dthRecharge = Dth::create([
                'service' => $request->service,
                'mobile_no' => $request->mobile_no,
                'amount' => $request->amount,
                'transaction_id' => $transactionId,
                'status' => 'pending'
            ]);

            // Simulate recharge processing (you can integrate with actual DTH API here)
            $this->processRecharge($dthRecharge);

            return response()->json([
                'status' => 'success',
                'message' => 'DTH recharge initiated successfully',
                'data' => $dthRecharge->fresh()
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to process DTH recharge',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified DTH recharge
     */
    public function show($id): JsonResponse
    {
        try {
            $dthRecharge = Dth::findOrFail($id);
            
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
            $dthRecharge = Dth::findOrFail($id);
            
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
            $dthRecharge = Dth::findOrFail($id);
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
     * Get recharge statistics
     */
    public function statistics(): JsonResponse
    {
        try {
            $stats = [
                'total_recharges' => Dth::count(),
                'pending_recharges' => Dth::where('status', 'pending')->count(),
                'completed_recharges' => Dth::where('status', 'completed')->count(),
                'failed_recharges' => Dth::where('status', 'failed')->count(),
                'total_amount' => Dth::where('status', 'completed')->sum('amount'),
                'today_recharges' => Dth::whereDate('created_at', today())->count(),
                'today_amount' => Dth::whereDate('created_at', today())
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

    /**
     * Process the recharge (simulate API call)
     * In real implementation, you would integrate with DTH provider's API
     */
    private function processRecharge(Dth $dthRecharge): void
    {
        try {
            // Simulate processing delay
            // In real implementation, you would call DTH provider's API here
            
            // For demo purposes, randomly set success/failed status
            $success = rand(1, 10) > 2; // 80% success rate for demo
            
            if ($success) {
                $dthRecharge->update([
                    'status' => 'completed'
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
}