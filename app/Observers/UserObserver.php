<?php

namespace App\Observers;

use App\Models\User;
use App\Services\WalletService;
use Illuminate\Support\Facades\Log;

class UserObserver
{
    protected $walletService;

    public function __construct(WalletService $walletService)
    {
        $this->walletService = $walletService;
    }

    public function created(User $user)
    {
        try {
            $this->walletService->createWalletForUser($user);
            Log::info('Wallet auto-created for new user', ['user_id' => $user->id]);
        } catch (\Exception $e) {
            Log::error('Failed to auto-create wallet for user', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);
        }
    }
}
