<?php

namespace App\Http\Controllers;

use App\Enums\DanhMucTaiLieu;
use App\Models\TaiLieu;
use App\Models\TuyenDuong;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\GiamSat;
use App\Models\DonVi;
use App\Http\Requests\StoreGiamSatRequest;
use App\Models\ToaDo;

class GiamSatController extends Controller
{
    public function index(Request $request)
    {
        $giam_sat = GiamSat::where('isdelete',0)
        ->where('giam_sat_id',null)
        ->whereRaw('tuyen_duong_id in (select id from tuyen_duong where isdelete = 0)')
        ->with([
            'toa_do',
            'tai_lieu',
            'tuyen_duong',
            'tuyen_duong.diem_dau_xa',
            'tuyen_duong.diem_cuoi_xa',
            'tuyen_duong.loai_tuyen_duong',
            'don_vi','doan_duong']);

        if($request->filled('ten_duong')){
            $giam_sat = $giam_sat->whereHas('tuyen_duong', function($query){
                $query->where('ten', 'like', '%'.request('ten_duong').'%');
            });
        }
        $giam_sat = $giam_sat->paginate(20);
        $tuyen_duong = TuyenDuong::where('isdelete',0)->where('tuyen_duong_id',null)->get();
        $don_vi = DonVi::where('isdelete',0)->get();
        if(isset($request->page)) {
            $stt = $request->page*20 - 19;
           } else {
            $stt = 1;
           }
        return Inertia::render('GiamSat/Index', compact('giam_sat', 'tuyen_duong', 'don_vi','stt'));
    }

    public function store(StoreGiamSatRequest $request)
    {
       $validated = $request->validated();
        // unset($validated['tai_lieu']);
        $giam_sat = GiamSat::updateOrCreate(['id' => $validated['id']],$validated);

        if($request->filled('route_geometry')){
            ToaDo::where('parent_id', $giam_sat->id)->delete();

            $coordinates = is_string($validated['route_geometry'])
            ? json_decode($validated['route_geometry'], true)['coordinates']
            : $validated['route_geometry']['coordinates'];

            foreach ($coordinates as $coordinate) {
                ToaDo::create([
                    'parent_id' => $giam_sat->id,
                    'lng' => $coordinate[0],
                    'lat' => $coordinate[1],
                    'type' => 'giam_sat',
                ]);
            }
        }
    }
    public function delete(Request $request)
    {
       $obj = GiamSat::find($request->id);
        $obj->isdelete = 1;
        $obj->save();
    }
}
