<?php

namespace App\Http\Controllers;

use App\Enums\DanhMucTaiLieu;
use App\Models\DonVi;
use App\Models\TaiLieu;
use App\Models\TuyenDuong;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\CapPhep;
use App\Http\Requests\StoreCapPhepRequest;
use App\Http\Requests\StoreCapPhepDiemRequest;
use Illuminate\Support\Facades\DB;

class CapPhepController extends Controller
{
    public function index(Request $request)
    {
        $cap_phep = CapPhep::where('isdelete',0)
        ->where('cap_phep_id',null)
        ->whereRaw('tuyen_duong_id in (select id from tuyen_duong where isdelete = 0)')
        ->with([
            'don_vi',
            'tai_lieu',
            'tuyen_duong',
            'doan_duong',
            'doan_duong.don_vi'
        ]);
        if($request->filled('ten_duong')){
            $cap_phep = $cap_phep->whereHas('tuyen_duong', function($query) use ($request){
                $query->where('ten', 'like', '%'.$request->ten_duong.'%');
            });
        }
        $cap_phep = $cap_phep->paginate(20);
        $don_vi = DonVi::where('isdelete',0)->get();
        $tuyen_duong = TuyenDuong::where('isdelete',0)->where('tuyen_duong_id',null)->get();
        if(isset($request->page)) {
            $stt = $request->page*20 - 19;
           } else {
            $stt = 1;
           }
        return Inertia::render('CapPhep/Index', compact('cap_phep', 'don_vi', 'tuyen_duong','stt'));
    }

    public function store(StoreCapPhepRequest $request)
    {
        $validated = $request->validated();
        unset($validated['tai_lieu']);
        $cap_phep = CapPhep::updateOrCreate(['id' => $validated['id']],$validated);
    }
   
    public function delete(Request $request)
    {
       $obj = CapPhep::find($request->id);
        $obj->isdelete = 1;
        $obj->save();
    }
}
