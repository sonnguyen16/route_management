<?php

namespace App\Http\Controllers;
use App\Models\ToaDo;
use App\Models\ToaDoKhac;
use App\Models\TuyenDuong;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BanDoController extends Controller
{
    public function index()
    {
        $tuyen_duong = TuyenDuong::with(['diem_dau_xa','diem_cuoi_xa','huyen', 'tai_lieu', 'don_vi', 'toa_do'])->get();
        $toa_do_khac = ToaDoKhac::with('tai_lieu')->get();
        return Inertia::render('Map/Index', compact('tuyen_duong', 'toa_do_khac'));
    }

    public function update_mota(Request $request)
    {
        $toa_do = ToaDoKhac::find($request->toa_do_id);
        $toa_do->update([
            'mo_ta' => $request->mo_ta,
        ]);
        $toa_do->save();
    }
}
