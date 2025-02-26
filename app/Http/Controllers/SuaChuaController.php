<?php

namespace App\Http\Controllers;

use App\Models\DonVi;
use App\Models\SuaChua;
use App\Models\SuaChuaDiem;
use App\Http\Requests\StoreSuaChuaRequest;
use App\Http\Requests\StoreSuaChuaDiemRequest;
use App\Models\TuyenDuong;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use App\Models\TaiLieu;
use App\Models\CauHinh;
use App\Enums\DanhMucTaiLieu;
use Illuminate\Support\Facades\DB;
use App\Models\ToaDo;

class SuaChuaController extends Controller
{
    public function index(Request $request)
    {
        $sua_chua = SuaChua::where('isdelete',0)
        ->where('sua_chua_id',null)
        ->whereRaw('tuyen_duong_id in (select id from tuyen_duong where isdelete = 0)')
        ->with([ 'tai_lieu','tuyen_duong', 'don_vi', 'nguoi_duyet','loai_sua_chua','doan_duong.toa_do','doan_duong.don_vi','doan_duong.loai_sua_chua']);
        if($request->filled('ten_duong')){
            $sua_chua = $sua_chua->whereHas('tuyen_duong', function($query) use ($request){
                $query->where('ten', 'like', '%'.$request->ten_duong.'%');
            });
        }

        if($request->filled('ngay_khoi_cong')){
            $sua_chua = $sua_chua->where('ngay_khoi_cong', '>=', $request->ngay_khoi_cong);
        }

        if($request->filled('ngay_hoan_thanh')){
            $sua_chua = $sua_chua->where('ngay_hoan_thanh', '<=', $request->ngay_hoan_thanh);
        }

        $sua_chua = $sua_chua->paginate(20);

        $tuyen_duong = TuyenDuong::where('isdelete',0)->where('tuyen_duong_id',null)->get();
        $don_vi = DonVi::where('isdelete',0)->get();
        $nguoi_duyet = User::all();
        $loai_sua_chua = CauHinh::where('loai','loai_sua_chua')->get();
        if(isset($request->page)) {
            $stt = $request->page*20 - 19;
           } else {
            $stt = 1;
           }
        return Inertia::render('SuaChua/Index', compact('sua_chua', 'tuyen_duong', 'don_vi', 'nguoi_duyet','loai_sua_chua','stt'));
    }

    public function store(StoreSuaChuaRequest $request)
    {
        $validated = $request->validated();
        unset($validated['tai_lieu']);
        $sua_chua = SuaChua::updateOrCreate(['id' => $validated['id']],$validated);

        if($request->filled('route_geometry')){
            ToaDo::where('parent_id', $sua_chua->id)->delete();

            $coordinates = is_string($validated['route_geometry'])
            ? json_decode($validated['route_geometry'], true)['coordinates']
            : $validated['route_geometry']['coordinates'];

            foreach ($coordinates as $coordinate) {
                ToaDo::create([
                    'parent_id' => $sua_chua->id,
                    'lng' => $coordinate[0],
                    'lat' => $coordinate[1],
                    'type' => 'sua_chua',
                ]);
            }
        }
    }
    public function delete(Request $request)
    {
       $obj = SuaChua::find($request->id);
        $obj->isdelete = 1;
        $obj->save();
    }
}
