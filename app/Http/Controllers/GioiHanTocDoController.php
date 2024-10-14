<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\GioiHanTocDo;
use App\Http\Requests\StoreGioiHanTocDoRequest;
use App\Models\TaiLieu;
use App\Enums\DanhMucTaiLieu;

class GioiHanTocDoController extends Controller
{
    public function index()
    {
        $gioi_han_toc_do = GioiHanTocDo::with(['tuyen_duong', 'don_vi'])->paginate(15);
        return Inertia::render('TocDo/Index', compact('gioi_han_toc_do'));
    }

    public function store(StoreGioiHanTocDoRequest $request)
    {
        $validated = $request->validated();
        unset($validated['tai_lieu']);
        $toc_do = GioiHanTocDo::updateOrCreate(['id' => $validated['id']],$validated);

        if($request->hasFile('tai_lieu')) {
            foreach ($validated('tai_lieu') as $file) {
                $originalName = $file->getClientOriginalName();
                $file = $file->store('tai_lieu/toc_do', 'public');

                TaiLieu::create([
                    'ten' => $originalName,
                    'file' => $file,
                    'tuyen_duong_id' => $toc_do->tuyen_duong_id,
                    'danh_muc' => DanhMucTaiLieu::toc_do->value,
                ]);
            }
        }
    }
}
