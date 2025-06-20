<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Services\WalletService;
use Illuminate\Support\Facades\Log;

class CreateWalletsForExistingUsers extends Command
{
    protected $signature = 'wallet:create-for-existing-users';
    protected $description = 'Create wallets for existing users who don\'t have wallets';

    private WalletService $walletService;

    public function __construct(WalletService $walletService)
    {
        parent::__construct();
        $this->walletService = $walletService;
    }

    public function handle()
    {
        $this->info('Creating wallets for existing users...');

        $usersWithoutWallets = User::whereDoesntHave('wallet')->get();
        
        $this->info("Found {$usersWithoutWallets->count()} users without wallets.");

        $successCount = 0;
        $failureCount = 0;

        foreach ($usersWithoutWallets as $user) {
            try {
                $wallet = $this->walletService->createWalletForUser($user);
                $this->info("✓ Created wallet for user ID: {$user->id} (Email: {$user->email})");
                $successCount++;
            } catch (\Exception $e) {
                $this->error("✗ Failed to create wallet for user ID: {$user->id} - {$e->getMessage()}");
                $failureCount++;
            }
        }

        $this->info("\nSummary:");
        $this->info("Successfully created: {$successCount} wallets");
        $this->info("Failed: {$failureCount} wallets");

        return Command::SUCCESS;
    }
}