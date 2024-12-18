<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ToaDoKhac;
use App\Models\TuyenDuong;
use Inertia\Inertia;

class DiemTaiNanController extends Controller
{
    public function index()
    {
        $tuyen_duong = TuyenDuong::with([
            'diem_dau_xa',
            'diem_cuoi_xa',
            'huyen',
            'tai_lieu',
            'don_vi',
            'toa_do'])->get();
        //    dd($tuyen_duong);
        $toa_do_khac = ToaDoKhac::where('loai',4)->with('tai_lieu')->get();
        return Inertia::render('DiemTaiNan/Index', compact('tuyen_duong', 'toa_do_khac'));
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
