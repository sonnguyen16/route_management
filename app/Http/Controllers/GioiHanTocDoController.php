<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\GioiHanTocDo;
use App\Http\Requests\StoreGioiHanTocDoRequest;
use App\Models\TaiLieu;
use App\Enums\DanhMucTaiLieu;
use App\Models\TuyenDuong;

class GioiHanTocDoController extends Controller
{
    public function index(Request $request)
    {
        $gioi_han_toc_do = GioiHanTocDo::with([
            'tai_lieu',
            'tuyen_duong',
            'tuyen_duong.diem_dau_xa',
            'tuyen_duong.diem_cuoi_xa',]);
        if($request->filled('ten_duong')) {
            $gioi_han_toc_do = $gioi_han_toc_do->whereHas('tuyen_duong', function($query) use ($request) {
                $query->where('ten', 'like', '%'.$request->ten_duong.'%');
            });
        }
        $gioi_han_toc_do = $gioi_han_toc_do->paginate(15);
        $tuyen_duong = TuyenDuong::all();
        return Inertia::render('TocDo/Index', compact('gioi_han_toc_do', 'tuyen_duong'));
    }

    public function store(StoreGioiHanTocDoRequest $request)
    {
        $validated = $request->validated();
        unset($validated['tai_lieu']);
        $toc_do = GioiHanTocDo::updateOrCreate(['id' => $validated['id']],$validated);

        if($request->hasFile('tai_lieu')) {
            foreach ($request->file('tai_lieu') as $file) {
                $originalName = $file->getClientOriginalName();
                $type = $file->getClientOriginalExtension();
                $file = $file->storeAs('files/1/gioi_han_toc_do', $originalName, 'public');

                TaiLieu::create([
                    'ten' => $originalName,
                    'file' => $file,
                    'loai' => $type,
                    'tuyen_duong_id' => $toc_do->tuyen_duong_id,
                    'danh_muc' => DanhMucTaiLieu::toc_do->value,
                ]);
            }
        }
    }
}
