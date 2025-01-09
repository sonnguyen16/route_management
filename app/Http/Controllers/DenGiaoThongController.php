<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreDenGiaoThongRequest;
use App\Models\DenGiaoThong;
use App\Models\TuyenDuong;
use Inertia\Inertia;
class DenGiaoThongController extends Controller
{
    public function index(Request $request)
    {
        $den_giao_thong = DenGiaoThong::where('isdelete',0)->with([
            'tai_lieu']);;
        if($request->filled('search')){
            $den_giao_thong = $den_giao_thong->where('nut_giao', 'like', '%'.$request->search.'%');
        }
        $den_giao_thong = $den_giao_thong->paginate(20);
        $tuyen_duong = TuyenDuong::where('isdelete',0)->where('tuyen_duong_id',null)->get();
        if(isset($request->page)) {
            $stt = $request->page*20 - 19;
           } else {
            $stt = 1;
           }
        return Inertia::render('DenGiaoThong/Index', compact('den_giao_thong', 'tuyen_duong','stt'));
    }

    public function store(StoreDenGiaoThongRequest $request)
    {
        $validated = $request->validated();
        unset($validated['tai_lieu']);
        $obj = DenGiaoThong::updateOrCreate(['id' => $validated['id']],$validated);
    }
    public function delete(Request $request)
    {
       $obj = DenGiaoThong::find($request->id);
        $obj->isdelete = 1;
        $obj->save();
    }
}
