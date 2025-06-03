<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Dth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class DthController extends Controller
{
    public function index()
    {
        $recharges = Dth::orderBy('created_at', 'desc')->paginate(10);
        return response()->json([
            'status' => 'success',
            'data' => $recharges
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'service' => 'required|string|max:50',
            'mobile_no' => 'required|string|max:20|regex:/^[0-9]+$/',
            'amount' => 'required|numeric|min:1|max:99999999.99',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $recharge = Dth::create([
                'service' => $request->service,
                'mobile_no' => $request->mobile_no,
                'amount' => $request->amount,
                'transaction_id' => $this->generateTransactionId(),
                'status' => 'Pending'
            ]);

            $this->processRecharge($recharge);

            return response()->json([
                'status' => 'success',
                'message' => 'DTH recharge initiated successfully',
                'data' => $recharge
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to process recharge',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $recharge = Dth::findOrFail($id);
            return response()->json([
                'status' => 'success',
                'data' => $recharge
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Recharge not found'
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:Pending,Success,Failed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $recharge = Dth::findOrFail($id);
            $recharge->update(['status' => $request->status]);

            return response()->json([
                'status' => 'success',
                'message' => 'Recharge status updated successfully',
                'data' => $recharge
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update recharge status',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $recharge = Dth::findOrFail($id);
            $recharge->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Recharge deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete recharge',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getByMobile($mobile)
    {
        $recharges = Dth::where('mobile_no', $mobile)
                        ->orderBy('created_at', 'desc')
                        ->get();

        return response()->json([
            'status' => 'success',
            'data' => $recharges
        ]);
    }

    public function getByService($service)
    {
        $recharges = Dth::where('service', $service)
                        ->orderBy('created_at', 'desc')
                        ->paginate(10);

        return response()->json([
            'status' => 'success',
            'data' => $recharges
        ]);
    }

    public function getStats()
    {
        $stats = [
            'total_recharges' => Dth::count(),
            'pending_recharges' => Dth::where('status', 'Pending')->count(),
            'successful_recharges' => Dth::where('status', 'Success')->count(),
            'failed_recharges' => Dth::where('status', 'Failed')->count(),
            'total_amount' => Dth::where('status', 'Success')->sum('amount'),
            'today_recharges' => Dth::whereDate('created_at', today())->count(),
            'today_amount' => Dth::whereDate('created_at', today())
                                ->where('status', 'Success')
                                ->sum('amount')
        ];

        return response()->json([
            'status' => 'success',
            'data' => $stats
        ]);
    }

    private function generateTransactionId()
    {
        return 'DTH' . date('YmdHis') . Str::upper(Str::random(6));
    }

    private function processRecharge($recharge)
    {
        $success = rand(1, 10) > 2;

        $recharge->status = $success ? 'Success' : 'Failed';
        $recharge->save(); // <-- use save() for better traceability
    }
}
