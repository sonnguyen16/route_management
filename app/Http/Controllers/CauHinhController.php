<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreCauHinhRequest;
use App\Models\CauHinh;
use Inertia\Inertia;

class CauHinhController extends Controller
{
    public function index(Request $request)
    {
        $cau_hinh = CauHinh::query();
        if($request->filled('search')){
            $cau_hinh = $cau_hinh->where('ten', 'like', '%'.$request->search.'%');
        }
        if($request->loai) {
            $cau_hinh = $cau_hinh->where('loai', str_replace('-','_',$request->loai));
        }
        $cau_hinh = $cau_hinh->paginate(10);
        $loai = str_replace('-','_',$request->loai);
        $ten = '';
        $linkback='';
       if($loai == 'loai_tuyen_duong') {
        $ten = 'Thêm loại tuyến đường';
        $linkback='tuyen-duong.index';
       }
       if($loai == 'phan_cap') {
        $ten = 'Thêm mã phân cấp';
        $linkback='tuyen-duong.index';
       }
       if($loai == 'loai_cau') {
        $ten = 'Thêm loại cầu';
        $linkback='cau.index';
       }
       if($loai == 'loai_ket_cau_nhip') {
        $ten = 'Thêm loại kế cấu nhịp';
        $linkback='cau.index';
       }
       if($loai == 'loai_sua_chua') {
        $ten = 'Thêm loại sửa chữa';
        $linkback='sua-chua.index';
       }
       
        return Inertia::render('CauHinh/Index', compact('cau_hinh','loai','ten','linkback'));
    }

    public function store(StoreCauHinhRequest $request)
    {
       
        $validated = $request->validated();       
        CauHinh::updateOrCreate(['id' => $validated['id']], $validated);
    }
}
