<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\RechargeApiPending;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str; 
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use \Illuminate\Support\Facades\Log;


class RechargeApiPendingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
        public function index(Request $request): JsonResponse
    {
          try {
            $query = RechargeApiPending::query();

            // Apply filters
            if ($request->has('status') && !empty($request->status)) {
                $query->byStatus($request->status);
            }

            if ($request->has('operatorname') && !empty($request->operatorname)) {
                $query->where('operatorname', 'like', '%' . $request->operatorname . '%');
            }

            if ($request->has('category') && !empty($request->category)) {
                $query->where('category', $request->category);
            }

            if ($request->has('biller_id') && !empty($request->biller_id)) {
                $query->where('biller_id', $request->biller_id);
            }

            if ($request->has('search') && !empty($request->search)) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('operatorname', 'like', '%' . $search . '%')
                      ->orWhere('name', 'like', '%' . $search . '%')
                      ->orWhere('biller_id', 'like', '%' . $search . '%');
                });
            }


            // Get paginated results
            $perPage = $request->get('per_page', 15);
            $rechargeapipending = $query->orderBy('created_at', 'desc')
                                         ->paginate($perPage);

            return response()->json([
                'status' => 'success',
                'message' => 'Recharge API success retrieved successfully',
                'data' => $rechargeapipending->items(),
                'pagination' => [
                    'current_page' => $rechargeapipending->currentPage(),
                    'last_page' => $rechargeapipending->lastPage(),
                    'per_page' => $rechargeapipending->perPage(),
                    'total' => $rechargeapipending->total(),
                ]
            ]);

        } catch (\Exception $e) {

            \Illuminate\Support\Facades\Log::error('Recharge Success Cases Index Error: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch recharge success cases',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
        try {
            // Validation rules
            $validator = Validator::make($request->all(), [
                'op' => 'required|string|max:255',
                // 'operatorname' => 'required|string|max:255',
                // 'category' => 'required|string|max:100',
                // 'name' => 'required|string|max:255',
                // 'biller_id' => 'required|string|max:255|unique:recharge_success_case,biller_id',
                // 'view_bill' => 'nullable|boolean',
                // 'bbps_enabled' => 'nullable|boolean',
                // 'regex' => 'nullable|string',
                // 'cn' => 'nullable|string|max:255',
                // 'ad1_with_regex' => 'nullable|string',
                // 'ad2' => 'nullable|string|max:255',
                // 'ad3' => 'nullable|string|max:255',
                // 'ad4' => 'nullable|string|max:255',
                // 'ad9' => 'nullable|string|max:255',
                // 'additional_parms_payment_api' => 'nullable|string'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Create new recharge success case
            $rechargeapipending = RechargeApiPending::create([
                'op' => $request->op,
                'operatorname' => $request->operatorname,
                'category' => $request->category,
                'view_bill' => $request->boolean('view_bill', false),
                'bbps_enabled' => $request->boolean('bbps_enabled', false),
                'regex' => $request->regex,
                'name' => $request->name,
                'cn' => $request->cn,
                'ad1_with_regex' => $request->ad1_with_regex,
                'ad2' => $request->ad2,
                'ad3' => $request->ad3,
                'ad4' => $request->ad4,
                'ad9' => $request->ad9,
                'additional_parms_payment_api' => $request->additional_parms_payment_api,
                'biller_id' => $request->biller_id,
            ]);

            Log::info('Recharge API Pending Created', [
                'status' => 'pending',
                'message' => 'Recharge API Pending',
                'data' => $rechargeapipending
            ]);

            return response()->json([
                'status' => 'pending',
                'message' => 'Recharge success case created successfully',
                'data' => $rechargeapipending
            ], 201);

        } catch (\Illuminate\Database\QueryException $e) {
           \Illuminate\Support\Facades\Log::error('Database Error in Recharge Success Cases Store: ' . $e->getMessage());
            
            return response()->json([
                'status' => 'error',
                'message' => 'Database error occurred',
                'error' => config('app.debug') ? $e->getMessage() : 'Duplicate entry or database constraint violation'
            ], 500);

        } catch (\Exception $e) {
           \Illuminate\Support\Facades\Log::error('Recharge Success Cases Store Error: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create recharge success case',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }
    
    /**
     * Display the specified resource.
     */
 public function show($id): JsonResponse
    {
        try {
            $dthRecharge = RechargeApiPending::findOrFail($id);
            
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
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
   public function update(Request $request, string $id): JsonResponse
    {
        try {
            $rechargeSuccessCase = RechargeApiPending::findOrFail($id);

            // Validation rules
            $validator = Validator::make($request->all(), [
                'op' => 'required|string|max:255',
                // 'operatorname' => 'required|string|max:255',
                // 'category' => 'required|string|max:100',
                // 'name' => 'required|string|max:255',
                // 'biller_id' => 'required|string|max:255|unique:recharge_success_case,biller_id,' . $id,
                // 'view_bill' => 'nullable|boolean',
                // 'bbps_enabled' => 'nullable|boolean',
                // 'regex' => 'nullable|string',
                // 'cn' => 'nullable|string|max:255',
                // 'ad1_with_regex' => 'nullable|string',
                // 'ad2' => 'nullable|string|max:255',
                // 'ad3' => 'nullable|string|max:255',
                // 'ad4' => 'nullable|string|max:255',
                // 'ad9' => 'nullable|string|max:255',
                // 'additional_parms_payment_api' => 'nullable|string'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Update recharge success case
            $rechargeSuccessCase->update([
                'op' => $request->op,
                'operatorname' => $request->operatorname,
                'category' => $request->category,
                'view_bill' => $request->boolean('view_bill', false),
                'bbps_enabled' => $request->boolean('bbps_enabled', false),
                'regex' => $request->regex,
                'name' => $request->name,
                'cn' => $request->cn,
                'ad1_with_regex' => $request->ad1_with_regex,
                'ad2' => $request->ad2,
                'ad3' => $request->ad3,
                'ad4' => $request->ad4,
                'ad9' => $request->ad9,
                'additional_parms_payment_api' => $request->additional_parms_payment_api,
                'biller_id' => $request->biller_id,
                'status' => $request->status,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Recharge API pending updated successfully',
                'data' => $rechargeSuccessCase->fresh()
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Recharge success case not found'
            ], 404);

        } catch (\Illuminate\Database\QueryException $e) {
           \Illuminate\Support\Facades\Log::error('Database Error in Recharge Success Cases Update: ' . $e->getMessage());
            
            return response()->json([
                'status' => 'error',
                'message' => 'Database error occurred',
                'error' => config('app.debug') ? $e->getMessage() : 'Duplicate entry or database constraint violation'
            ], 500);

        } catch (\Exception $e) {
           \Illuminate\Support\Facades\Log::error('Recharge Success Cases Update Error: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update recharge success case',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }                
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $rechargeSuccessCase = RechargeApiPending::findOrFail($id);
            $rechargeSuccessCase->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Recharge success case deleted successfully'
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Recharge success case not found'
            ], 404);

        } catch (\Exception $e) {
           \Illuminate\Support\Facades\Log::error('Recharge Success Cases Delete Error: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete recharge success case',
                'error' => config('app.debug') ? $e->getMessage() : 'Internal server error'
            ], 500);
        }
    }
}
