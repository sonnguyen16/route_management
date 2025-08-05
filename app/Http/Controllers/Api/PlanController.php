<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PlanController extends Controller
{
    /**
     * Get all plans
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        if ($user->isLeader()) {
            // Lãnh đạo có thể xem tất cả plans
            $plans = Plan::with('user')->orderBy('date', 'desc')->get();
        } else {
            // Đội trưởng chỉ xem plans của mình
            $plans = Plan::where('user_id', $user->id)->orderBy('date', 'desc')->get();
        }

        return response()->json([
            'success' => true,
            'data' => $plans
        ]);
    }

    /**
     * Store a new plan
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        // Chỉ lãnh đạo mới có thể tạo plans
        if (!$user->isLeader()) {
            return response()->json([
                'success' => false,
                'message' => 'Chỉ lãnh đạo mới có thể tạo kế hoạch'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'lng' => 'required|numeric|between:-180,180',
            'lat' => 'required|numeric|between:-90,90',
            'description' => 'required|string|max:1000',
            'date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $plan = Plan::create([
            'user_id' => $user->id,
            'lng' => $request->lng,
            'lat' => $request->lat,
            'description' => $request->description,
            'date' => $request->date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
        ]);

        return response()->json([
            'success' => true,
            'data' => $plan->load('user')
        ], 201);
    }

    /**
     * Get a specific plan
     */
    public function show($id)
    {
        $user = Auth::user();

        $plan = Plan::with('user')->find($id);

        if (!$plan) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy kế hoạch'
            ], 404);
        }

        // Check permissions
        if (!$user->isLeader() && $plan->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Không có quyền xem kế hoạch này'
            ], 403);
        }

        return response()->json([
            'success' => true,
            'data' => $plan
        ]);
    }

    /**
     * Update a plan
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();

        $plan = Plan::find($id);

        if (!$plan) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy kế hoạch'
            ], 404);
        }

        // Check permissions
        if (!$user->isLeader() && $plan->user_id !== $user->id) {
            return response()->json([
                'success' => false,
                'message' => 'Không có quyền sửa kế hoạch này'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'lng' => 'sometimes|numeric|between:-180,180',
            'lat' => 'sometimes|numeric|between:-90,90',
            'description' => 'sometimes|string|max:1000',
            'date' => 'sometimes|date|after_or_equal:today',
            'start_time' => 'sometimes|date_format:H:i',
            'end_time' => 'sometimes|date_format:H:i|after:start_time',
            'status' => 'sometimes|in:pending,active,completed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $plan->update($request->only([
            'lng', 'lat', 'description', 'date', 'start_time', 'end_time', 'status'
        ]));

        return response()->json([
            'success' => true,
            'data' => $plan->load('user')
        ]);
    }

    /**
     * Delete a plan
     */
    public function destroy($id)
    {
        $user = Auth::user();

        $plan = Plan::find($id);

        if (!$plan) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy kế hoạch'
            ], 404);
        }

        // Check permissions - chỉ lãnh đạo mới có thể xóa
        if (!$user->isLeader()) {
            return response()->json([
                'success' => false,
                'message' => 'Chỉ lãnh đạo mới có thể xóa kế hoạch'
            ], 403);
        }

        $plan->delete();

        return response()->json([
            'success' => true,
            'message' => 'Đã xóa kế hoạch thành công'
        ]);
    }

    /**
     * Get plans for today
     */
    public function today()
    {
        $user = Auth::user();

        $query = Plan::with('user')->whereDate('date', today());

        if (!$user->isLeader()) {
            $query->where('user_id', $user->id);
        }

        $plans = $query->orderBy('start_time')->get();

        return response()->json([
            'success' => true,
            'data' => $plans
        ]);
    }
}
