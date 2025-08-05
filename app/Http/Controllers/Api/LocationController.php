<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LocationHistory;
use App\Models\User;
use App\Events\LocationUpdated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LocationController extends Controller
{
    /**
     * Store a new location point
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'lat' => 'required|numeric|between:-90,90',
            'lng' => 'required|numeric|between:-180,180',
            'accuracy' => 'nullable|numeric|min:0',
            'altitude' => 'nullable|numeric',
            'heading' => 'nullable|numeric|between:0,360',
            'speed' => 'nullable|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $user = Auth::user();

        // Chỉ đội trưởng mới có thể gửi location
        if (!$user->isCaptain()) {
            return response()->json([
                'success' => false,
                'message' => 'Chỉ đội trưởng mới có thể gửi vị trí'
            ], 403);
        }

        $locationHistory = LocationHistory::create([
            'user_id' => $user->id,
            'lat' => $request->lat,
            'lng' => $request->lng,
            'accuracy' => $request->accuracy,
            'altitude' => $request->altitude,
            'heading' => $request->heading,
            'speed' => $request->speed,
            'recorded_at' => now(),
        ]);

        // Broadcast location update
        broadcast(new LocationUpdated($user, $locationHistory));

        return response()->json([
            'success' => true,
            'data' => $locationHistory
        ]);
    }

    /**
     * Get location history for current user
     */
    public function getHistory(Request $request)
    {
        $user = Auth::user();
        $hours = $request->get('hours', 24); // Default 24 hours

        $locations = LocationHistory::where('user_id', $user->id)
            ->where('recorded_at', '>=', now()->subHours($hours))
            ->orderBy('recorded_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $locations
        ]);
    }

    /**
     * Get active captains' locations (for leaders)
     */
    public function getActiveCaptains(Request $request)
    {
        $user = Auth::user();

        if (!$user->isLeader()) {
            return response()->json([
                'success' => false,
                'message' => 'Chỉ lãnh đạo mới có thể xem vị trí các đội trưởng'
            ], 403);
        }

        $minutes = $request->get('minutes', 5); // Active trong 5 phút gần nhất

        // Lấy location gần nhất của mỗi captain trong khoảng thời gian cho phép
        $recentTime = now()->subMinutes($minutes);

        $captains = User::where('role', 'captain')
            ->whereHas('locationHistories', function ($query) use ($recentTime) {
                $query->where('recorded_at', '>=', $recentTime)
                    ->where('is_active', true);
            })
            ->get()
            ->map(function ($captain) use ($recentTime) {
                $latestLocation = LocationHistory::where('user_id', $captain->id)
                    ->where('recorded_at', '>=', $recentTime)
                    ->where('is_active', true)
                    ->orderBy('recorded_at', 'desc')
                    ->first();

                return [
                    'user_id' => $captain->id,
                    'user_name' => $captain->name,
                    'location' => $latestLocation
                ];
            })
            ->filter(function ($captain) {
                return $captain['location'] !== null;
            });

        return response()->json([
            'success' => true,
            'data' => $captains
        ]);
    }

    /**
     * Get specific captain's location history (for leaders)
     */
    public function getCaptainHistory(Request $request, $captainId)
    {
        $user = Auth::user();

        if (!$user->isLeader()) {
            return response()->json([
                'success' => false,
                'message' => 'Chỉ lãnh đạo mới có thể xem lịch sử vị trí đội trưởng'
            ], 403);
        }

        $captain = User::where('id', $captainId)
            ->where('role', 'captain')
            ->first();

        if (!$captain) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy đội trưởng'
            ], 404);
        }

        $hours = $request->get('hours', 24);

        $locations = LocationHistory::where('user_id', $captainId)
            ->where('recorded_at', '>=', now()->subHours($hours))
            ->orderBy('recorded_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'captain' => $captain,
                'locations' => $locations
            ]
        ]);
    }
}
