<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTuyenDuongRequest;
use App\Models\TaiLieu;
use App\Models\TuyenDuong;
use Inertia\Inertia;
use App\Models\Huyen;

class TuyenDuongController extends Controller
{
    public function index()
    {
        $tuyen_duong = TuyenDuong::with(['diem_dau_xa','diem_cuoi_xa','huyen', 'tai_lieu'])->paginate(15);
        $huyen = Huyen::with('xa')->get();
        return Inertia::render('Duong/Index', compact('tuyen_duong', 'huyen'));
    }

    public function store(StoreTuyenDuongRequest $request)
    {
        $validated = $request->validated();
        unset($validated['tai_lieu']);

        $tuyen_duong = TuyenDuong::updateOrCreate(['id' => $validated['id']],$validated);

        if($request->hasFile('tai_lieu')) {
            foreach ($request->file('tai_lieu') as $file) {
                $originalName = $file->getClientOriginalName();
                $file = $file->store('tuyen_duong', 'public');

                TaiLieu::create([
                    'tuyen_duong_id' => $tuyen_duong->id,
                    'file' => $file,
                    'ten' => $originalName,
                ]);
            }
        }
    }
}
