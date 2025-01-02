<?php

namespace App\Http\Controllers;

use App\Enums\DanhMucTaiLieu;
use App\Models\TaiLieu;
use App\Models\TuyenDuong;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use App\Models\Huyen;
use App\Http\Requests\StoreTuyenDuongRequest;
use App\Models\DonVi;
use App\Models\CauHinh;

class TuyenDuongController extends Controller
{
    public function index(Request $request)
    {
        $tuyen_duong = TuyenDuong::where('isdelete',0)->with(['diem_dau_xa','diem_dau_huyen','diem_cuoi_huyen','diem_cuoi_xa','huyen', 'tai_lieu', 'don_vi', 'loai_tuyen_duong','phan_cap','doan_duong','doan_duong.diem_dau_xa','doan_duong.diem_dau_huyen','doan_duong.diem_cuoi_huyen','doan_duong.diem_cuoi_xa','doan_duong.don_vi']);
        //->where('tuyen_duong_id',null)
        if($request->filled('ten_duong')){
            $tuyen_duong = $tuyen_duong->where('ten', 'like', '%'.request('ten_duong').'%');
        }
        $tuyen_duong = $tuyen_duong->paginate(18)->withQueryString();
        $huyen = Huyen::with('xa')->get();
        $don_vi = DonVi::all();
        $loai_tuyen_duong = CauHinh::where('loai','loai_tuyen_duong')->get();
        $phan_cap = CauHinh::where('loai','phan_cap')->get();
        return Inertia::render('Duong/Index', compact('tuyen_duong', 'huyen', 'don_vi','loai_tuyen_duong','phan_cap'));
    }

    public function store(StoreTuyenDuongRequest $request)
    {
        $validated = $request->validated();
        $validated['key'] = Str::slug($validated['ten']);
        TuyenDuong::updateOrCreate(['id' => $validated['id']], $validated);
    }
    public function deleteDiem(Request $request)
    {
       /* $obj = TuyenDuongDiem::find($request->id);
        $obj->isdelete = 1;
        $obj->save();
        */
    }
    
}
