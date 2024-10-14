<?php

namespace App\Http\Controllers;

use App\Enums\DanhMucTaiLieu;
use App\Models\TaiLieu;
use Inertia\Inertia;
use App\Models\CapPhep;
use App\Http\Requests\StoreCapPhepRequest;

class CapPhepController extends Controller
{
    public function index()
    {
        $don_vi = CapPhep::with(['don_vi', 'tuyen_duong'])->paginate(15);
        return Inertia::render('CapPhep/Index', compact('don_vi'));
    }

    public function store(StoreCapPhepRequest $request)
    {
        $validated = $request->validated();
        unset($validated['tai_lieu']);
        $cap_phep = CapPhep::updateOrCreate(['id' => $validated['id']],$validated);

        if($request->hasFile('tai_lieu')) {
            foreach ($validated('tai_lieu') as $file) {
                $originalName = $file->getClientOriginalName();
                $file = $file->store('tai_lieu/cap_phep', 'public');

                TaiLieu::create([
                    'ten' => $originalName,
                    'file' => $file,
                    'tuyen_duong_id' => $cap_phep->tuyen_duong_id,
                    'danh_muc' => DanhMucTaiLieu::cap_phep->value,
                ]);
            }
        }
    }
}
