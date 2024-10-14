<?php

namespace App\Http\Controllers;

use App\Models\CongVan;
use Inertia\Inertia;
use App\Http\Requests\StoreCongVanRequest;
use App\Models\TaiLieu;
use App\Enums\DanhMucTaiLieu;

class CongVanController extends Controller
{
    public function index()
    {
        $cong_van = CongVan::with(['don_vi', 'nguoi_xu_ly'])->paginate(15);
        return Inertia::render('CongVan/Index', compact('cong_van'));
    }

    public function store(StoreCongVanRequest $request)
    {
        $validated = $request->validated();
        unset($validated['tai_lieu']);
        $cong_van = CongVan::updateOrCreate(['id' => $validated['id']],$validated);

        if($request->hasFile('tai_lieu')) {
            foreach ($validated('tai_lieu') as $file) {
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
