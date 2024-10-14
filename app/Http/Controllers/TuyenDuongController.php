<?php

namespace App\Http\Controllers;

use App\Models\TuyenDuong;
use Inertia\Inertia;
use App\Models\Huyen;
use App\Http\Requests\StoreTuyenDuongRequest;
use App\Models\DonVi;

class TuyenDuongController extends Controller
{
    public function index()
    {
        $tuyen_duong = TuyenDuong::with(['diem_dau_xa','diem_cuoi_xa','huyen', 'tai_lieu', 'don_vi'])
            ->paginate(18);
        $huyen = Huyen::with('xa')->get();
        $don_vi = DonVi::all();
        return Inertia::render('Duong/Index', compact('tuyen_duong', 'huyen', 'don_vi'));
    }

    public function store(StoreTuyenDuongRequest $request)
    {
        $validated = $request->validated();
        TuyenDuong::updateOrCreate(['id' => $validated['id']],$validated);
    }
}
