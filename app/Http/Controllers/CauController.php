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
        $cau = Cau::where('isdelete',0)->with(['loai_ket_cau_nhip','tuyen_duong','loai_cau','tai_lieu']);
        if($request->filled('search')){
            $cau = $cau->where('nut_giao', 'like', '%'.$request->search.'%');
        }
        $cau = $cau->paginate(20)->withQueryString();
       
        $tuyen_duong = TuyenDuong::where('isdelete',0)->where('tuyen_duong_id',null)->get();
        $loai_ket_cau_nhip = CauHinh::where('loai','loai_ket_cau_nhip')->get();
        $loai_cau = CauHinh::where('loai','loai_cau')->get();
        if(isset($request->page)) {
            $stt = $request->page*20 - 19;
           } else {
            $stt = 1;
           }
        return Inertia::render('Cau/Index', compact('cau', 'tuyen_duong','loai_ket_cau_nhip','loai_cau','stt'));
    }

    public function store(StoreCauRequest $request)
    {
        $validated = $request->validated();
        unset($validated['tai_lieu']);
        $obj = Cau::updateOrCreate(['id' => $validated['id']],$validated);
    }
    public function delete(Request $request)
    {
       $obj = Cau::find($request->id);
        $obj->isdelete = 1;
        $obj->save();
    }
}
