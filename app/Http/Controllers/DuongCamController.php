<?php

namespace App\Http\Controllers;

use App\Models\DonVi;
use App\Models\DuongCam;
use App\Models\DiemCam;
use App\Http\Requests\StoreDuongCamRequest;
use App\Http\Requests\StoreDuongCamDiemRequest;
use App\Models\TuyenDuong;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\TaiLieu;
use App\Enums\DanhMucTaiLieu;
use Illuminate\Support\Facades\DB;
class DuongCamController extends Controller
{
    public function index(Request $request)
    {
        $duong_cam = DuongCam::where('isdelete',0)
        ->where('duong_cam_id',null)
        ->whereRaw('tuyen_duong_id in (select id from tuyen_duong where isdelete = 0)')
        ->with([
            'tai_lieu',
            'don_vi_quyet_dinh',
            'don_vi_thuc_hien',
            'tuyen_duong',
            'doan_duong',
            'doan_duong.don_vi_quyet_dinh',
            'doan_duong.don_vi_thuc_hien'
        ]);
        if($request->filled('ten_duong')){
            $duong_cam = $duong_cam->whereHas('tuyen_duong', function($query) use ($request){
                $query->where('ten', 'like', '%'.$request->ten_duong.'%');
            });
        }

        if($request->filled('tu_ngay')){
            $duong_cam = $duong_cam->where('tu_ngay', '>=', $request->tu_ngay);
        }

        if($request->filled('den_ngay')){
            $duong_cam = $duong_cam->where('den_ngay', '<=', $request->den_ngay);
        }

        $duong_cam = $duong_cam->paginate(20);
        $don_vi = DonVi::where('isdelete',0)->get();
        $tuyen_duong = TuyenDuong::where('isdelete',0)->where('tuyen_duong_id',null)->get();
        if(isset($request->page)) {
            $stt = $request->page*20 - 19;
           } else {
            $stt = 1;
           }
        return Inertia::render('DuongCam/Index', compact('duong_cam', 'don_vi', 'tuyen_duong','stt'));
    }

    public function store(StoreDuongCamRequest $request)
    {
        $validated = $request->validated();
        unset($validated['tai_lieu']);
        $duong_cam = DuongCam::updateOrCreate(['id' => $validated['id']],$validated);
    }
    public function delete(Request $request)
    {
       $obj = DuongCam::find($request->id);
        $obj->isdelete = 1;
        $obj->save();
    }

}
