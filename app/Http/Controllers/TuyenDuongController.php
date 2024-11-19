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
        $tuyen_duong = TuyenDuong::with(['diem_dau_xa','diem_cuoi_xa','huyen', 'tai_lieu', 'don_vi', 'loai_tuyen_duong','phan_cap','loai_tuan_tra']);

        if($request->filled('ten_duong')){
            $tuyen_duong = $tuyen_duong->where('ten', 'like', '%'.request('ten_duong').'%');
        }
        $tuyen_duong = $tuyen_duong->paginate(18)->withQueryString();
        $huyen = Huyen::with('xa')->get();
        $don_vi = DonVi::all();
        $loai_tuyen_duong = CauHinh::where('loai','tuyen_duong')->get();
        $phan_cap = CauHinh::where('loai','phan_cap')->get();
        $loai_tuan_tra = CauHinh::where('loai','tuan_tra')->get();
        return Inertia::render('Duong/Index', compact('tuyen_duong', 'huyen', 'don_vi','loai_tuyen_duong','phan_cap','loai_tuan_tra'));
    }

    public function store(StoreTuyenDuongRequest $request)
    {
        $validated = $request->validated();
        $validated['key'] = Str::slug($validated['ten']);
       
        TuyenDuong::updateOrCreate(['id' => $validated['id']], $validated);
    }
}
