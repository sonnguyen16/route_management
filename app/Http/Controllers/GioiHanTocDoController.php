<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\GioiHanTocDo;
use App\Http\Requests\StoreGioiHanTocDoRequest;
use App\Models\TaiLieu;
use App\Enums\DanhMucTaiLieu;
use App\Models\TuyenDuong;
use App\Models\DonVi;

class GioiHanTocDoController extends Controller
{
    public function index(Request $request)
    {
        $gioi_han_toc_do = GioiHanTocDo::with([
            'tai_lieu',
            'tuyen_duong',
            'tuyen_duong.diem_dau_xa',
            'tuyen_duong.diem_cuoi_xa','tuyen_duong.loai_tuyen_duong','don_vi_thuc_hien','doan_duong','don_vi','doan_duong.don_vi','doan_duong.don_vi_thuc_hien']);
        if($request->filled('ten_duong')) {
            $gioi_han_toc_do = $gioi_han_toc_do->whereHas('tuyen_duong', function($query) use ($request) {
                $query->where('ten', 'like', '%'.$request->ten_duong.'%');
            });
        }
        $gioi_han_toc_do = $gioi_han_toc_do->paginate(15);
        $tuyen_duong = TuyenDuong::all();
        $don_vi = DonVi::all();
        
        return Inertia::render('TocDo/Index', compact('gioi_han_toc_do', 'tuyen_duong','don_vi',));
    }

    public function store(StoreGioiHanTocDoRequest $request)
    {
        $validated = $request->validated();
        unset($validated['tai_lieu']);
        $toc_do = GioiHanTocDo::updateOrCreate(['id' => $validated['id']],$validated);
    }
    public function storeDiemGioiHanTocDo(Request $request)
    {
        if(isset($request->id)){
            $obj = DiemGioiHanTocDo::find($request->id);
        } else {
            $obj = new DiemGioiHanTocDo;
        }
        $obj->gioi_han_toc_do_id = $request->gioi_han_toc_do_id;
        $obj->toc_do = $request->toc_do;
        $obj->tu_km = $request->tu_km;
        $obj->den_km = $request->den_km;
        $obj->tu_ngay = $request->tu_ngay;
        $obj->den_ngay = $request->den_ngay;
        $obj->noi_dung = $request->noi_dung;
        $obj->save();
    }
    public function deleteDiemGioiHanTocDo(Request $request)
    {
        $obj = DiemGioiHanTocDo::find($request->id);
        $obj->isdelete = 1;
        $obj->save();
    }
    
}
