<?php

namespace App\Http\Controllers;

use App\Models\DuongCam;
use App\Http\Requests\StoreDuongCamRequest;
use Inertia\Inertia;
use App\Models\TaiLieu;
use App\Enums\DanhMucTaiLieu;

class DuongCamController extends Controller
{
    public function index()
    {
        $don_vi = DuongCam::with(['don_vi_quyet_dinh','don_vi_thuc_hien', 'tuyen_duong'])->paginate(15);
        return Inertia::render('DuongCam/Index', compact('don_vi'));
    }

    public function store(StoreDuongCamRequest $request)
    {
        $validated = $request->validated();
        unset($validated['tai_lieu']);
        $duong_cam = DuongCam::updateOrCreate(['id' => $validated['id']],$validated);

        if($request->hasFile('tai_lieu')) {
            foreach ($validated('tai_lieu') as $file) {
                $originalName = $file->getClientOriginalName();
                $file = $file->store('tai_lieu/duong_cam', 'public');

                TaiLieu::create([
                    'ten' => $originalName,
                    'file' => $file,
                    'tuyen_duong_id' => $duong_cam->tuyen_duong_id,
                    'danh_muc' => DanhMucTaiLieu::duong_cam->value,
                ]);
            }
        }
    }
}
