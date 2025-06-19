<?php

namespace App\Http\Controllers;

use App\Services\MobiKwikService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    private MobiKwikService $mobikwikService;

    public function __construct(MobiKwikService $mobikwikService)
    {
        $this->mobikwikService = $mobikwikService;
    }

    public function handleMobiKwikWebhook(Request $request)
    {
        Log::info('MobiKwik webhook received', $request->all());

        try {
            $result = $this->mobikwikService->handleCallback($request->all());
            
            if ($result['success']) {
                return response()->json(['status' => 'success']);
            }
            
            return response()->json(['status' => 'failed'], 400);
            
        } catch (\Exception $e) {
            Log::error('Webhook processing failed: ' . $e->getMessage());
            return response()->json(['status' => 'error'], 500);
        }
    }
}