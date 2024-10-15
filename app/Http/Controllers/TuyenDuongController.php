<?php

namespace App\Http\Controllers;

use App\Enums\DanhMucTaiLieu;
use App\Models\TaiLieu;
use App\Models\TuyenDuong;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Huyen;
use App\Http\Requests\StoreTuyenDuongRequest;
use App\Models\DonVi;

class TuyenDuongController extends Controller
{
    public function index(Request $request)
    {
        $tuyen_duong = TuyenDuong::with(['diem_dau_xa','diem_cuoi_xa','huyen', 'tai_lieu', 'don_vi']);

        if($request->filled('ten_duong')){
            $tuyen_duong = $tuyen_duong->where('ten', 'like', '%'.request('ten_duong').'%');
        }
        $tuyen_duong = $tuyen_duong->paginate(18)->withQueryString();
        $huyen = Huyen::with('xa')->get();
        $don_vi = DonVi::all();
        return Inertia::render('Duong/Index', compact('tuyen_duong', 'huyen', 'don_vi'));
    }

    public function store(StoreTuyenDuongRequest $request)
    {
        $validated = $request->validated();
        $tuyen_duong = TuyenDuong::updateOrCreate(['id' => $validated['id']],$validated);

        if($request->hasFile('tai_lieu')) {
            foreach ($request->file('tai_lieu') as $file) {
                $originalName = $file->getClientOriginalName();
                $type = $file->getClientOriginalExtension();
                $file = $file->storeAs('files/1/tuyen_duong', $originalName, 'public');

                TaiLieu::create([
                    'ten' => $originalName,
                    'file' => $file,
                    'loai' => $type,
                    'tuyen_duong_id' => $tuyen_duong->id,
                    'danh_muc' => DanhMucTaiLieu::tuyen_duong->value,
                ]);
            }
        }
    }
}
