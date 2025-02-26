<?php

namespace App\Http\Controllers;

use App\Models\CapPhep;
use App\Models\Cau;
use App\Models\DenGiaoThong;
use App\Models\DuongCam;
use App\Models\GiamSat;
use App\Models\GioiHanTocDo;
use App\Models\SuaChua;
use App\Models\ToaDoKhac;
use App\Models\TuyenDuong;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BanDoController extends Controller
{
    public function index()
    {
        $sua_chua = SuaChua::with([
            'doan_duong.toa_do',
            'tuyen_duong',
        ])->where('isdelete', 0)->get();

        $duong_cam = DuongCam::with([
            'doan_duong.toa_do',
            'tuyen_duong',
        ])->where('isdelete', 0)->get();

        $giam_sat = GiamSat::with([
            'doan_duong.toa_do',
            'tuyen_duong',
        ])->where('isdelete', 0)->get();

        $toc_do = GioiHanTocDo::with([
            'doan_duong.toa_do',
            'tuyen_duong',
        ])->where('isdelete', 0)->get();

        $cap_phep = CapPhep::with([
            'doan_duong.toa_do',
            'tuyen_duong',
        ])->where('isdelete', 0)->get();

        $cau = Cau::where('isdelete', 0)->get();
        $den_giao_thong = DenGiaoThong::where('isdelete', 0)->get();

        return Inertia::render('Map/Index', compact('sua_chua', 'duong_cam', 'giam_sat', 'toc_do', 'cap_phep', 'cau', 'den_giao_thong'));
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
