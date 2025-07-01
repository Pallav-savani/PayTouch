<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\KycVerification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class KycController extends Controller
{
    /**
     * Get account information for the authenticated user
     */
    public function getAccountInfo()
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated'
                ], 401);
            }

            // Get KYC data
            $kycData = KycVerification::where('user_id', $user->id)->first();
            
            if (!$kycData) {
                return response()->json([
                    'success' => false,
                    'message' => 'KYC data not found'
                ], 404);
            }

            // Prepare account info with all required fields
            $accountInfo = [
                'member_id' => $kycData->member_id ?? '',
                'member_no' => $kycData->member_no ?? '',
                'member_code' => $kycData->member_code ?? '',
                'mobile_no' => $kycData->mobile_no ?? $user->mobile ?? '',
                'member_name' => $kycData->member_name ?? '',
                'birth_date' => $kycData->birth_date ? date('d/m/Y', strtotime($kycData->birth_date)) : '',
                'age' => $kycData->age ?? '',
                'home_address' => $kycData->home_address ?? '',
                'city_name' => $kycData->city_name ?? '',
                'email' => $kycData->email ?? $user->email ?? '',
                'status' => $kycData->status ?? 'Inactive',
                'discount_pattern' => $kycData->discount_pattern ?? 'DEMO RT(0%)',
                'pan_card_no' => $kycData->pan_card_no ?? '',
                'aadhaar_no' => $kycData->aadhaar_no ?? '',
                'gst_no' => $kycData->gst_no ?? '',
                'registration_date' => $kycData->created_at ? $kycData->created_at->format('d/m/Y g:i:s A') : '',
                'activation_date' => $kycData->activation_date ? date('d/m/Y g:i:s A', strtotime($kycData->activation_date)) : 'Not Activated',
                'password_change_date' => $kycData->password_change_date ? date('d/m/Y g:i:s A', strtotime($kycData->password_change_date)) : '-',
                'last_topup_date' => $kycData->last_topup_date ? date('d/m/Y g:i:s A', strtotime($kycData->last_topup_date)) : '-',
                'balance' => $this->formatBalance($user->wallet_balance ?? 0),
                'dmr_balance' => $this->formatBalance($kycData->dmr_balance ?? 0), // Fixed: Use formatBalance instead of 'N/A'
                'discount' => $this->formatBalance($kycData->discount ?? 0)
            ];

            return response()->json([
                'success' => true,
                'kyc_data' => $accountInfo,
                'message' => 'Account info retrieved successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching account info',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Format balance with words
     */
    private function formatBalance($amount)
    {
        if ($amount == 0) {
            return '0.00 [ Rupees Only]';
        }
        
        $amount = number_format($amount, 2);
        $words = $this->convertNumberToWords($amount);
        
        return $amount . ' [ ' . $words . ' Only]';
    }

    /**
     * Convert number to words (simplified version)
     */
    private function convertNumberToWords($amount)
    {
        $parts = explode('.', $amount);
        $rupees = (int)str_replace(',', '', $parts[0]);
        $paise = isset($parts[1]) ? (int)str_replace(',', '', $parts[1]) : 0;
        
        $rupeesWords = $this->numberToWords($rupees);
        $result = 'Rupees ' . ucfirst($rupeesWords);
        
        if ($paise > 0) {
            $paiseWords = $this->numberToWords($paise);
            $result .= ' and ' . ucfirst($paiseWords) . ' Paise ';
        }
        
        return $result;
    }

    /**
     * Convert number to words helper
     */
    private function numberToWords($number)
    {
        $ones = array(
            0 => 'Zero', 1 => 'One', 2 => 'Two', 3 => 'Three', 4 => 'Four',
            5 => 'Five', 6 => 'Six', 7 => 'Seven', 8 => 'Eight', 9 => 'Nine',
            10 => 'Ten', 11 => 'Eleven', 12 => 'Twelve', 13 => 'Thirteen',
            14 => 'Fourteen', 15 => 'Fifteen', 16 => 'Sixteen', 17 => 'Seventeen',
            18 => 'Eighteen', 19 => 'Nineteen'
        );

        $tens = array(
            20 => 'Twenty', 30 => 'Thirty', 40 => 'Forty', 50 => 'Fifty',
            60 => 'Sixty', 70 => 'Seventy', 80 => 'Eighty', 90 => 'Ninety'
        );

        if ($number < 20) {
            return $ones[$number];
        } elseif ($number < 100) {
            $ten = intval($number / 10) * 10;
            $unit = $number % 10;
            return $tens[$ten] . ($unit ? ' ' . $ones[$unit] : '');
        } elseif ($number < 1000) {
            $hundred = intval($number / 100);
            $remainder = $number % 100;
            return $ones[$hundred] . ' Hundred' . ($remainder ? ' ' . $this->numberToWords($remainder) : '');
        } elseif ($number < 100000) {
            $thousand = intval($number / 1000);
            $remainder = $number % 1000;
            return $this->numberToWords($thousand) . ' Thousand' . ($remainder ? ' ' . $this->numberToWords($remainder) : '');
        } elseif ($number < 10000000) {
            $lakh = intval($number / 100000);
            $remainder = $number % 100000;
            return $this->numberToWords($lakh) . ' Lakh' . ($remainder ? ' ' . $this->numberToWords($remainder) : '');
        } else {
            $crore = intval($number / 10000000);
            $remainder = $number % 10000000;
            return $this->numberToWords($crore) . ' Crore' . ($remainder ? ' ' . $this->numberToWords($remainder) : '');
        }
    }

    /**
     * Display the KYC form or completed status
     */
    public function index()
    {
        try {
            $user = Auth::user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated'
                ], 401);
            }

            // Otherwise, return KYC status and data
            $kycData = KycVerification::where('user_id', $user->id)->first();

            // Check if user is authenticated and KYC is completed
            if ($user && $user->kyc_completed) {
                // Return a JSON response indicating redirect to welcome page (always 200)
                return response()->json([
                    'success' => true,
                    'redirect' => url('/welcome'),
                    'message' => 'KYC already completed. Redirecting to welcome page.',
                    'kyc_data' => $kycData
                ]);
            }

            return response()->json([
                'success' => true,
                'kyc_completed' => $user->kyc_completed ?? false,
                'kyc_data' => $kycData
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching KYC data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store KYC verification data
     */
    public function store(Request $request)
    {
        try {
            $user = Auth::user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated'
                ], 401);
            }

            // Check if user already has completed KYC
            if ($user->kyc_completed) {
                return response()->json([
                    'success' => false,
                    'message' => 'KYC already completed for this user'
                ], 400);
            }

            // Validation rules - only validate user input fields
            $validator = Validator::make($request->all(), [
                'mobile_no' => 'required|string|max:15',
                'member_name' => 'required|string|max:255',
                'birth_date' => 'required|date',
                'age' => 'required|integer|min:18|max:100',
                'home_address' => 'required|string|max:500',
                'city_name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'pan_card_no' => 'required|string|size:10|regex:/^[A-Z]{5}[0-9]{4}[A-Z]{1}$/',
                'aadhaar_no' => 'required|string|size:12|regex:/^[0-9]{12}$/',
                'gst_no' => 'nullable|string|max:15'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            DB::beginTransaction();

            try {
                // Prepare data with defaults and user input
                $kycData = [
                    // User input fields
                    'user_id' => $user->id,
                    'mobile_no' => $request->mobile_no,
                    'member_name' => $request->member_name,
                    'birth_date' => $request->birth_date,
                    'age' => $request->age,
                    'home_address' => $request->home_address,
                    'city_name' => $request->city_name,
                    'email' => $request->email,
                    'pan_card_no' => $request->pan_card_no,
                    'aadhaar_no' => $request->aadhaar_no,
                    'gst_no' => $request->gst_no,
                    
                    // Auto-generated fields
                    'member_id' => 'MID' . mt_rand(100000, 999999),
                    'member_no' => 'MNO' . mt_rand(100000, 999999),
                    
                    // Default fields
                    'status' => 'Pending',
                    'discount_pattern' => 'DEMO RT(0%)',
                    'balance' => 0.00,
                    'dmr_balance' => 0.00, // Fixed: Use numeric value instead of 'N/A'
                    'discount' => 0.00,
                    'kyc_completed' => true,
                    
                    // Date fields
                    'registration_date' => now(),
                    'activation_date' => now(),
                    'password_change_date' => null,
                    'last_topup_date' => null,
                ];

                // Create or update KYC record
                $kycRecord = KycVerification::updateOrCreate(
                    ['user_id' => $user->id],
                    $kycData
                );

                // Update user's KYC status
                $user->update(['kyc_completed' => true]);
// dd($kycRecord);
                DB::commit();

                return response()->json([
                    'success' => true,
                    'message' => 'KYC information submitted successfully',
                    'data' => $kycRecord
                ]);

            } catch (\Exception $e) {
                DB::rollback();
                throw $e;
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error submitting KYC data: ' . $e->getMessage(),
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get KYC data for a specific user
     */
    public function show($id = null)
    {
        try {
            $userId = $id ?? Auth::id();
            $kycData = KycVerification::where('user_id', $userId)->first();

            if (!$kycData) {
                return response()->json([
                    'success' => false,
                    'message' => 'KYC data not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $kycData
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching KYC data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update KYC data
     */
    public function update(Request $request, $id)
    {
        try {
            $kycData = KycVerification::findOrFail($id);

            // Check if the authenticated user owns this KYC record
            if ($kycData->user_id !== Auth::id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access'
                ], 403);
            }

            $validator = Validator::make($request->all(), [
                'member_id' => 'sometimes|required|string|max:255',
                'member_no' => 'sometimes|required|string|max:255',
                'mobile_no' => 'sometimes|required|string|max:15',
                'member_name' => 'sometimes|required|string|max:255',
                'birth_date' => 'sometimes|required|date',
                'age' => 'sometimes|required|integer|min:18|max:100',
                'home_address' => 'sometimes|required|string|max:500',
                'city_name' => 'sometimes|required|string|max:255',
                'email' => 'sometimes|required|email|max:255',
                'pan_card_no' => 'sometimes|required|string|size:10|regex:/^[A-Z]{5}[0-9]{4}[A-Z]{1}$/',
                'aadhaar_no' => 'sometimes|required|string|size:12|regex:/^[0-9]{12}$/',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Only update fields that are present in the request and allowed
            $updatableFields = [
                'member_id',
                'member_no',
                'mobile_no',
                'member_name',
                'birth_date',
                'age',
                'home_address',
                'city_name',
                'email',
                'pan_card_no',
                'aadhaar_no',
                'firm_name',
                'firm_address',
                'gst_no',
            ];
            $dataToUpdate = [];
            foreach ($updatableFields as $field) {
                if ($request->has($field)) {
                    $dataToUpdate[$field] = $request->input($field);
                }
            }
            $kycData->update($dataToUpdate);

            return response()->json([
                'success' => true,
                'message' => 'KYC data updated successfully',
                'data' => $kycData
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating KYC data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete KYC data
     */
    public function destroy($id)
    {
        try {
            $kycData = KycVerification::findOrFail($id);

            // Check if the authenticated user owns this KYC record
            if ($kycData->user_id !== Auth::id()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access'
                ], 403);
            }

            $kycData->delete();

            // Update user's KYC status
            Auth::user()->update(['kyc_completed' => false]);

            return response()->json([
                'success' => true,
                'message' => 'KYC data deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting KYC data',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}