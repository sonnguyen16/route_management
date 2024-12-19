<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreDenGiaoThongRequest;
use App\Models\DenGiaoThong;
use App\Models\TuyenDuong;
use Inertia\Inertia;
class DenGiaoThongController extends Controller
{
    public function index(Request $request)
    {
        $den_giao_thong = DenGiaoThong::with([
            'tai_lieu']);;



        
        if($request->filled('search')){
            $den_giao_thong = $den_giao_thong->where('nut_giao', 'like', '%'.$request->search.'%');
        }
        $den_giao_thong = $den_giao_thong->paginate(15);
        $tuyen_duong = TuyenDuong::all();
       
        return Inertia::render('DenGiaoThong/Index', compact('den_giao_thong', 'tuyen_duong'));
    }

    public function store(StoreDenGiaoThongRequest $request)
    {
        $validated = $request->validated();
        unset($validated['tai_lieu']);
        $obj = DenGiaoThong::updateOrCreate(['id' => $validated['id']],$validated);
    }
}
