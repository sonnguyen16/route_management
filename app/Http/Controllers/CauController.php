<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreCauRequest;
use App\Models\Cau;
use App\Models\TuyenDuong;
use App\Models\CauHinh;
use Inertia\Inertia;
class CauController extends Controller
{
    public function index(Request $request)
    {
        $cau = Cau::with(['loai_ket_cau_nhip','tuyen_duong','loai_cau','tai_lieu']);
        if($request->filled('search')){
            $cau = $cau->where('nut_giao', 'like', '%'.$request->search.'%');
        }
        $cau = $cau->paginate(18)->withQueryString();
       
        $tuyen_duong = TuyenDuong::all();
        $loai_ket_cau_nhip = CauHinh::where('loai','loai_ket_cau_nhip')->get();
        $loai_cau = CauHinh::where('loai','loai_cau')->get();
       
        return Inertia::render('Cau/Index', compact('cau', 'tuyen_duong','loai_ket_cau_nhip','loai_cau'));
    }

    public function store(StoreCauRequest $request)
    {
        $validated = $request->validated();
        unset($validated['tai_lieu']);
        $obj = Cau::updateOrCreate(['id' => $validated['id']],$validated);
    }
}
