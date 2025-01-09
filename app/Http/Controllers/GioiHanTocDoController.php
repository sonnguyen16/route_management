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
        $gioi_han_toc_do = GioiHanTocDo::where('isdelete',0)->where('gioi_han_toc_do_id',null)->with([
            'tai_lieu',
            'tuyen_duong',
            'tuyen_duong.diem_dau_xa',
            'tuyen_duong.diem_cuoi_xa','tuyen_duong.loai_tuyen_duong','don_vi_thuc_hien','doan_duong','don_vi','doan_duong.don_vi','doan_duong.don_vi_thuc_hien']);
        if($request->filled('ten_duong')) {
            $gioi_han_toc_do = $gioi_han_toc_do->whereHas('tuyen_duong', function($query) use ($request) {
                $query->where('ten', 'like', '%'.$request->ten_duong.'%');
            });
        }
        $gioi_han_toc_do = $gioi_han_toc_do->paginate(20);
        $tuyen_duong = TuyenDuong::where('isdelete',0)->where('tuyen_duong_id',null)->get();
        $don_vi = DonVi::where('isdelete',0)->get();
        if(isset($request->page)) {
            $stt = $request->page*20 - 19;
           } else {
            $stt = 1;
           }
        return Inertia::render('TocDo/Index', compact('gioi_han_toc_do', 'tuyen_duong','don_vi','stt'));
    }

    public function store(StoreGioiHanTocDoRequest $request)
    {
        $validated = $request->validated();
        unset($validated['tai_lieu']);
        $toc_do = GioiHanTocDo::updateOrCreate(['id' => $validated['id']],$validated);
    }
    public function delete(Request $request)
    {
       $obj = GioiHanTocDo::find($request->id);
        $obj->isdelete = 1;
        $obj->save();
    }
    
}
