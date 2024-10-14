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
        $cong_van = CongVan::with(['don_vi', 'nguoi_xu_ly']);
        if($request->filled('ten')) {
            $cong_van = $cong_van->where('ten', 'like', '%'.$request->ten.'%');
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
                $file = $file->store('tai_lieu/cong_van', 'public');

                TaiLieu::create([
                    'ten' => $originalName,
                    'file' => $file,
                    'tuyen_duong_id' => $cong_van->tuyen_duong_id,
                    'danh_muc' => DanhMucTaiLieu::cong_van->value,
                ]);
            }
        }
    }
}
