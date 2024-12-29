<?php

namespace App\Http\Controllers;

use App\Enums\DanhMucTaiLieu;
use App\Models\DonVi;
use App\Models\TaiLieu;
use App\Models\TuyenDuong;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\CapPhep;
use App\Models\CapPhepDiem;
use App\Http\Requests\StoreCapPhepRequest;
use App\Http\Requests\StoreCapPhepDiemRequest;

class CapPhepController extends Controller
{
    public function index(Request $request)
    {
        $cap_phep = CapPhep::with([
            'don_vi',
            'tai_lieu',
            'tuyen_duong',
            'tuyen_duong.diem_dau_xa',
            'tuyen_duong.diem_cuoi_xa',
            'cap_phep_diem'
        ]);
        if($request->filled('ten_duong')){
            $cap_phep = $cap_phep->whereHas('tuyen_duong', function($query) use ($request){
                $query->where('ten', 'like', '%'.$request->ten_duong.'%');
            });
        }
        $cap_phep = $cap_phep->paginate(15);
        $don_vi = DonVi::all();
        $tuyen_duong = TuyenDuong::all();
        return Inertia::render('CapPhep/Index', compact('cap_phep', 'don_vi', 'tuyen_duong'));
    }

    public function store(StoreCapPhepRequest $request)
    {
        $validated = $request->validated();
        unset($validated['tai_lieu']);
        $cap_phep = CapPhep::updateOrCreate(['id' => $validated['id']],$validated);
    }
    public function storeCapPhepDiem(StoreCapPhepDiemRequest $request)
    {
        $validated = $request->validated();
        $cap_phep = CapPhepDiem::updateOrCreate(['id' => $validated['id']],$validated);
    }
    public function deleteDiemCapPhep(Request $request)
    {
        $obj = CapPhepDiem::find($request->id);
        $obj->isdelete = 1;
        $obj->save();
    }
}
