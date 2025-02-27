<?php

namespace App\Http\Controllers;

use App\Enums\DanhMucTaiLieu;
use App\Models\TaiLieu;
use App\Models\TuyenDuong;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use App\Models\Huyen;
use App\Http\Requests\StoreTuyenDuongRequest;
use App\Models\DonVi;
use App\Models\CauHinh;
use App\Models\SuaChua;
use App\Models\GiamSat;
use App\Models\ToaDo;

class TuyenDuongController extends Controller
{
    public function index(Request $request)
    {
        $tuyen_duong = TuyenDuong::where('isdelete',0)->where('tuyen_duong_id',null)->with(['diem_dau_xa','diem_dau_huyen','diem_cuoi_huyen','diem_cuoi_xa','huyen', 'tai_lieu', 'don_vi', 'loai_tuyen_duong','phan_cap','doan_duong.toa_do', 'toa_do' ,'doan_duong.diem_dau_xa','doan_duong.diem_dau_huyen','doan_duong.diem_cuoi_huyen','doan_duong.diem_cuoi_xa','doan_duong.don_vi']);

        if($request->filled('ten_duong')){
            $tuyen_duong = $tuyen_duong->where('ten', 'like', '%'.request('ten_duong').'%');
        }
        $tuyen_duong = $tuyen_duong->paginate(20)->withQueryString();
        $huyen = Huyen::with('xa')->get();
        $don_vi = DonVi::where('isdelete',0)->get();
        $loai_tuyen_duong = CauHinh::where('loai','loai_tuyen_duong')->get();
        $phan_cap = CauHinh::where('loai','phan_cap')->get();
       if(isset($request->page)) {
        $stt = $request->page*20 - 19;
       } else {
        $stt = 1;
       }
        return Inertia::render('Duong/Index', compact('tuyen_duong', 'huyen', 'don_vi','loai_tuyen_duong','phan_cap','stt'));
    }

    public function store(StoreTuyenDuongRequest $request)
    {

        $validated = $request->validated();
        $validated['key'] = Str::slug($validated['ten']);

        $obj = TuyenDuong::updateOrCreate(['id' => $validated['id']], $validated);

        if(!isset($request->id) && !isset($request->tuyen_duong_id)) {
            $s = new GiamSat;
            $s->tuyen_duong_id = $obj->id;
            $s->don_vi_id  = $obj->don_vi_id;
            $s->save();
            $c = new SuaChua;
            $c->tuyen_duong_id = $obj->id;
            $c->save();
        }

        if($request->filled('route_geometry')){
            ToaDo::where('parent_id', $obj->id)->delete();

            $coordinates = is_string($validated['route_geometry'])
            ? json_decode($validated['route_geometry'], true)['coordinates']
            : $validated['route_geometry']['coordinates'];

            foreach ($coordinates as $coordinate) {
                ToaDo::create([
                    'parent_id' => $obj->id,
                    'lng' => $coordinate[0],
                    'lat' => $coordinate[1],
                    'type' => 'tuyen_duong',
                ]);
            }
        }
    }
    public function delete(Request $request)
    {
       $obj = TuyenDuong::find($request->id);
        $obj->isdelete = 1;
        $obj->save();
    }

}
