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

class DuongCamController extends Controller
{
    public function index(Request $request)
    {
        $duong_cam = DuongCam::with([
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

        $duong_cam = $duong_cam->paginate(15);
        $don_vi = DonVi::all();
        $tuyen_duong = TuyenDuong::all();

        return Inertia::render('DuongCam/Index', compact('duong_cam', 'don_vi', 'tuyen_duong'));
    }

    public function store(StoreDuongCamRequest $request)
    {
        $validated = $request->validated();
        unset($validated['tai_lieu']);
        $duong_cam = DuongCam::updateOrCreate(['id' => $validated['id']],$validated);
    }

    public function storeDiemCam(StoreDuongCamDiemRequest $request)
    {

        $validated = $request->validated();
        $duong_cam = DiemCam::updateOrCreate(['id' => $validated['id']],$validated);
    }
    public function deleteDiem(Request $request)
    {
       /* $obj = TuyenDuongDiem::find($request->id);
        $obj->isdelete = 1;
        $obj->save();
        */
    }

}
