<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DenGiaoThong;
use Illuminate\Http\Request;

class DenGiaoThongController extends Controller
{
    public function index()
    {
        $denGiaoThong = DenGiaoThong::where('isdelete', 0)
            ->whereNotNull('lat')
            ->whereNotNull('lng')
            ->get();

        return response()->json($denGiaoThong);
    }

    public function show($id)
    {
        $denGiaoThong = DenGiaoThong::with('tai_lieu')
            ->where('isdelete', 0)
            ->findOrFail($id);

        return response()->json($denGiaoThong);
    }
}
