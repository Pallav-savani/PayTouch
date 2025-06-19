<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MobileRecharge;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class MobileRechargeController extends Controller
{
    // POST /api/recharge/submit
    public function submit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mobile_no' => 'required|digits:10',
            'operator'  => 'required|string',
            'circle'    => 'required|string',
            'amount'    => 'required|numeric|min:1|max:10000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        // Simulate recharge processing and status
        $status = 'Success'; // In real scenario, integrate with recharge API
        $txn_id = strtoupper(Str::random(12));

        $recharge = MobileRecharge::create([
            'mobile_no' => $request->mobile_no,
            'operator'  => $request->operator,
            'circle'    => $request->circle,
            'amount'    => $request->amount,
            'txn_id'    => $txn_id,
            'status'    => $status,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Recharge successful!',
            'data'    => $recharge
        ]);
    }

    // GET /api/recharge/history
    public function history()
    {
        $history = MobileRecharge::orderBy('created_at', 'desc')->limit(20)->get();

        return response()->json($history);
    }
}