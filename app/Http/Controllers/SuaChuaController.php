<?php

namespace App\Http\Controllers;

use App\Models\DonVi;
use App\Models\SuaChua;
use App\Http\Requests\StoreSuaChuaRequest;
use App\Models\TuyenDuong;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\TaiLieu;
use App\Enums\DanhMucTaiLieu;

class SuaChuaController extends Controller
{
    public function index(Request $request)
    {
        $sua_chua = SuaChua::with(['tuyen_duong', 'don_vi', 'nguoi_duyet']);
        if($request->filled('ten_duong')){
            $sua_chua = $sua_chua->whereHas('tuyen_duong', function($query) use ($request){
                $query->where('ten', 'like', '%'.$request->ten_duong.'%');
            });
        }
        $sua_chua = $sua_chua->paginate(15);
        $tuyen_duong = TuyenDuong::all();
        $don_vi = DonVi::all();
        $nguoi_duyet = User::all();
        return Inertia::render('SuaChua/Index', compact('sua_chua', 'tuyen_duong', 'don_vi', 'nguoi_duyet'));
    }

    public function store(StoreSuaChuaRequest $request)
    {
        $validated = $request->validated();
        unset($validated['tai_lieu']);
        $sua_chua = SuaChua::updateOrCreate(['id' => $validated['id']],$validated);

        if($request->hasFile('tai_lieu')) {
            foreach ($request->file('tai_lieu') as $file) {
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
