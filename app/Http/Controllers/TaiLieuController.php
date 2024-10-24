<?php

namespace App\Http\Controllers;

use App\Enums\DanhMucTaiLieu;
use App\Http\Requests\StoreTaiLieuRequest;
use Illuminate\Http\Request;
use App\Models\TaiLieu;
use Illuminate\Support\Facades\Storage;

class TaiLieuController extends Controller
{
    public function delete(Request $request)
    {
        $tai_lieu = TaiLieu::find($request->id);
        $path = $tai_lieu->file;
        Storage::disk('public')->delete($path);
        $tai_lieu->delete();
    }

    public function store(StoreTaiLieuRequest $request)
    {
        $validated = $request->validated();

        if($request->hasFile('file')) {
            foreach ($request->file('file') as $file) {
                $originalName = $file->getClientOriginalName();
                $dung_luong = $file->getSize();
                $type = $file->getClientOriginalExtension();
                $key = $this->getEnumCaseNameByValue($validated['danh_muc']);
                $path = 'files/1/' . $key;
                $file = $file->storeAs($path , $originalName, 'public');

                TaiLieu::create([
                    'ten' => $originalName,
                    'file' => $file,
                    'loai' => $type,
                    'tuyen_duong_id' => $validated['tuyen_duong_id'] ?? null,
                    'cong_van_id' => $validated['cong_van_id'] ?? null,
                    'toa_do_id' => $validated['toa_do_id'] ?? null,
                    'danh_muc' => $validated['danh_muc'],
                    'dung_luong' => $dung_luong,
                ]);
            }
        }
    }

    public function updateDetail(Request $request)
    {
        $tai_lieu = TaiLieu::find($request->id);
        $tai_lieu->update([
            'mo_ta' => $request->mo_ta,
        ]);
        $tai_lieu->save();
    }

    function getEnumCaseNameByValue(int $value): ?string {
        foreach (DanhMucTaiLieu::cases() as $case) {
            if ($case->value === $value) {
                return $case->name;
            }
        }
        return null;
    }

}
