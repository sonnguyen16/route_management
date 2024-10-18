<?php

namespace App\Http\Controllers;

use App\Models\CongVan;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Requests\StoreCongVanRequest;
use App\Models\TaiLieu;
use App\Enums\DanhMucTaiLieu;
use App\Models\User;
use App\Models\DonVi;

class CongVanController extends Controller
{
    public function index(Request $request)
    {
        $cong_van = CongVan::with(['tai_lieu','don_vi', 'nguoi_xu_ly']);
        if($request->filled('ten')) {
            $cong_van = $cong_van->where('ten', 'like', '%'.$request->ten.'%');
        }

        if($request->filled('ngay_gui')) {
            $cong_van = $cong_van->where('ngay_gui', '>=', $request->ngay_gui);
        }

        if($request->filled('ngay_nhan')) {
            $cong_van = $cong_van->where('ngay_nhan', '<=', $request->ngay_nhan);
        }

        $cong_van = $cong_van->paginate(15);
        $nguoi_xu_ly = User::all();
        $don_vi = DonVi::all();
        return Inertia::render('CongVan/Index', compact('cong_van', 'nguoi_xu_ly', 'don_vi'));
    }

    public function store(StoreCongVanRequest $request)
    {
        $validated = $request->validated();
        unset($validated['tai_lieu']);
        $cong_van = CongVan::updateOrCreate(['id' => $validated['id']],$validated);

        if($request->hasFile('tai_lieu')) {
            foreach ($request->file('tai_lieu') as $file) {
                $originalName = $file->getClientOriginalName();
                $type = $file->getClientOriginalExtension();
                $file = $file->storeAs('files/1/cong_van', $originalName, 'public');

                TaiLieu::create([
                    'ten' => $originalName,
                    'file' => $file,
                    'loai' => $type,
                    'cong_van_id' => $cong_van->cong_van_id,
                    'danh_muc' => DanhMucTaiLieu::cong_van->value,
                ]);
            }
        }
    }
}
