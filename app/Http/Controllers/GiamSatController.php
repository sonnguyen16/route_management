<?php

namespace App\Http\Controllers;

use App\Enums\DanhMucTaiLieu;
use App\Models\TaiLieu;
use App\Models\TuyenDuong;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\GiamSat;
use App\Models\DonVi;
use App\Http\Requests\StoreGiamSatRequest;


class GiamSatController extends Controller
{
    public function index(Request $request)
    {
        $giam_sat = GiamSat::with([
            'tai_lieu',
            'tuyen_duong',
            'tuyen_duong.diem_dau_xa',
            'tuyen_duong.diem_cuoi_xa',
            'don_vi']);

        if($request->filled('ten_duong')){
            $giam_sat = $giam_sat->whereHas('tuyen_duong', function($query){
                $query->where('ten', 'like', '%'.request('ten_duong').'%');
            });
        }
        $giam_sat = $giam_sat->paginate(15);
        $tuyen_duong = TuyenDuong::all();
        $don_vi = DonVi::all();
        return Inertia::render('GiamSat/Index', compact('giam_sat', 'tuyen_duong', 'don_vi'));
    }

    public function store(StoreGiamSatRequest $request)
    {
        $validated = $request->validated();
        unset($validated['tai_lieu']);
        $giam_sat = GiamSat::updateOrCreate(['id' => $validated['id']],$validated);
    }
}
