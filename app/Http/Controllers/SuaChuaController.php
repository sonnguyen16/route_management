<?php

namespace App\Http\Controllers;

use App\Models\SuaChua;
use App\Http\Requests\StoreSuaChuaRequest;
use Inertia\Inertia;
use App\Models\TaiLieu;
use App\Enums\DanhMucTaiLieu;

class SuaChuaController extends Controller
{
    public function index()
    {
        $sua_chua = SuaChua::with(['tuyen_duong', 'don_vi', 'nguoi_duyet'])->paginate(15);
        return Inertia::render('SuaChua/Index', compact('sua_chua'));
    }

    public function store(StoreSuaChuaRequest $request)
    {
        $validated = $request->validated();
        unset($validated['tai_lieu']);
        $sua_chua = SuaChua::updateOrCreate(['id' => $validated['id']],$validated);

        if($request->hasFile('tai_lieu')) {
            foreach ($validated('tai_lieu') as $file) {
                $originalName = $file->getClientOriginalName();
                $file = $file->store('tai_lieu/sua_chua', 'public');

                TaiLieu::create([
                    'ten' => $originalName,
                    'file' => $file,
                    'tuyen_duong_id' => $sua_chua->tuyen_duong_id,
                    'danh_muc' => DanhMucTaiLieu::sua_chua->value,
                ]);
            }
        }
    }
}
