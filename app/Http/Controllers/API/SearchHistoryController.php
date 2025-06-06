<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\SearchHistory;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class SearchHistoryController extends Controller
{
    /**
     * Store a new search history record
     */
    public function store(Request $request): JsonResponse
    {
        try {
            // Validate the request
            $validator = Validator::make($request->all(), [
                'customer_id' => 'nullable|string|max:15',
                'transaction_id' => 'nullable|string|max:100',
                'status' => 'nullable|in:success,pending,failed,unknown',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Ensure at least one search parameter is provided
            if (!$request->customer_id && !$request->transaction_id) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Either customer_id or transaction_id must be provided'
                ], 422);
            }

            // Create search history record
            $searchHistory = SearchHistory::create([
                'customer_id' => $request->customer_id,
                'transaction_id' => $request->transaction_id,
                'status' => $request->status ?? 'unknown',
                'search_time' => Carbon::now(),
                'user_ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Search history saved successfully',
                'data' => [
                    'id' => $searchHistory->id,
                    'customer_id' => $searchHistory->customer_id,
                    'transaction_id' => $searchHistory->transaction_id,
                    'status' => $searchHistory->status,
                    'search_time' => $searchHistory->formatted_search_time,
                ]
            ], 201);

        } catch (\Exception $e) {
            // \Log::error('Search History Store Error: ' . $e->getMessage(), [
            //     'request_data' => $request->all(),
            //     'trace' => $e->getTraceAsString()
            // ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to save search history'
            ], 500);
        }
    }

    /**
     * Get search history records
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $query = SearchHistory::query();

            // Apply filters
            if ($request->has('customer_id') && $request->customer_id) {
                $query->byCustomer($request->customer_id);
            }

            if ($request->has('transaction_id') && $request->transaction_id) {
                $query->byTransaction($request->transaction_id);
            }

            if ($request->has('status') && $request->status) {
                $query->byStatus($request->status);
            }

            if ($request->has('days') && $request->days) {
                $query->recent($request->days);
            } else {
                $query->recent(30); // Default to last 30 days
            }

            // Get paginated results
            $perPage = $request->get('per_page', 15);
            $searchHistories = $query->orderBy('search_time', 'desc')
                                   ->paginate($perPage);

            return response()->json([
                'status' => 'success',
                'data' => $searchHistories->items(),
                'pagination' => [
                    'current_page' => $searchHistories->currentPage(),
                    'last_page' => $searchHistories->lastPage(),
                    'per_page' => $searchHistories->perPage(),
                    'total' => $searchHistories->total(),
                ]
            ]);

        } catch (\Exception $e) {
            // \Log::error('Search History Index Error: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch search history'
            ], 500);
        }
    }

    /**
     * Get search statistics
     */
    public function statistics(Request $request): JsonResponse
    {
        try {
            $days = $request->get('days', 30);
            $startDate = Carbon::now()->subDays($days);

            $stats = [
                'total_searches' => SearchHistory::where('search_time', '>=', $startDate)->count(),
                'unique_customers' => SearchHistory::where('search_time', '>=', $startDate)
                                                 ->whereNotNull('customer_id')
                                                 ->distinct('customer_id')
                                                 ->count(),
                'unique_transactions' => SearchHistory::where('search_time', '>=', $startDate)
                                                    ->whereNotNull('transaction_id')
                                                    ->distinct('transaction_id')
                                                    ->count(),
                'status_breakdown' => SearchHistory::where('search_time', '>=', $startDate)
                                                 ->selectRaw('status, COUNT(*) as count')
                                                 ->groupBy('status')
                                                 ->pluck('count', 'status'),
                'daily_searches' => SearchHistory::where('search_time', '>=', $startDate)
                                                ->selectRaw('DATE(search_time) as date, COUNT(*) as count')
                                                ->groupBy('date')
                                                ->orderBy('date')
                                                ->pluck('count', 'date'),
            ];

            return response()->json([
                'status' => 'success',
                'data' => $stats,
                'period' => [
                    'days' => $days,
                    'start_date' => $startDate->format('Y-m-d'),
                    'end_date' => Carbon::now()->format('Y-m-d'),
                ]
            ]);

        } catch (\Exception $e) {
            // \Log::error('Search History Statistics Error: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch search statistics'
            ], 500);
        }
    }

    /**
     * Get most searched items
     */
    public function mostSearched(Request $request): JsonResponse
    {
        try {
            $days = $request->get('days', 30);
            $limit = $request->get('limit', 10);
            $startDate = Carbon::now()->subDays($days);

            $mostSearchedCustomers = SearchHistory::where('search_time', '>=', $startDate)
                                                 ->whereNotNull('customer_id')
                                                 ->selectRaw('customer_id, COUNT(*) as search_count')
                                                 ->groupBy('customer_id')
                                                 ->orderBy('search_count', 'desc')
                                                 ->limit($limit)
                                                 ->get();

            $mostSearchedTransactions = SearchHistory::where('search_time', '>=', $startDate)
                                                    ->whereNotNull('transaction_id')
                                                    ->selectRaw('transaction_id, COUNT(*) as search_count')
                                                    ->groupBy('transaction_id')
                                                    ->orderBy('search_count', 'desc')
                                                    ->limit($limit)
                                                    ->get();

            return response()->json([
                'status' => 'success',
                'data' => [
                    'most_searched_customers' => $mostSearchedCustomers,
                    'most_searched_transactions' => $mostSearchedTransactions,
                ],
                'period' => [
                    'days' => $days,
                    'limit' => $limit,
                ]
            ]);

        } catch (\Exception $e) {
            // \Log::error('Most Searched Error: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch most searched data'
            ], 500);
        }
    }

    /**
     * Delete old search history records
     */
    public function cleanup(Request $request): JsonResponse
    {
        try {
            $days = $request->get('days', 90); // Default: delete records older than 90 days
            $cutoffDate = Carbon::now()->subDays($days);

            $deletedCount = SearchHistory::where('search_time', '<', $cutoffDate)->delete();

            return response()->json([
                'status' => 'success',
                'message' => "Deleted {$deletedCount} old search history records",
                'data' => [
                    'deleted_count' => $deletedCount,
                    'cutoff_date' => $cutoffDate->format('Y-m-d H:i:s'),
                ]
            ]);

        } catch (\Exception $e) {
            // \Log::error('Search History Cleanup Error: ' . $e->getMessage());

            return response()->json([
                'status' => 'error',
                'message' => 'Failed to cleanup search history'
            ], 500);
        }
    }
}
