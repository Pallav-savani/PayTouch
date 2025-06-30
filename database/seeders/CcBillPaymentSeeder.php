<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CcBillPayment;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class CcBillPaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        
        if ($users->count() > 0) {
            foreach ($users as $user) {
                // Create sample CC bill payments for each user
                for ($i = 1; $i <= 5; $i++) {
                    $reqid = 'CC' . time() . rand(1000, 9999) . $i;
                    
                    CcBillPayment::create([
                        'user_id' => $user->id,
                        'uid' => $user->id,
                        'pwd' => $user->password,
                        'cn' => encrypt('4111111111111111'), // Sample card number
                        'op' => ['HDFC', 'ICICI', 'SBI', 'AXIS', 'KOTAK'][rand(0, 4)],
                        'cir' => ['MUM', 'DEL', 'BLR', 'CHN', 'KOL'][rand(0, 4)],
                        'amt' => rand(100, 5000),
                        'reqid' => $reqid,
                        'ad9' => 'CUST' . rand(1000, 9999),
                        'ad3' => 'BILL' . rand(1000, 9999),
                        'status' => ['pending', 'success', 'failed'][rand(0, 2)],
                        'transaction_id' => 'TXN' . time() . rand(1000, 9999),
                        'operator_ref' => 'OP' . time() . rand(100, 999),
                        'response_message' => 'Sample payment response',
                        'created_at' => now()->subDays(rand(1, 30)),
                    ]);

                    
                }
            }
        }
    }
}