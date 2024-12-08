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
       if($loai == 'loai_tuyen_duong') $ten = 'Thêm loại tuyến đường';
       if($loai == 'phan_cap') $ten = 'Thêm mã phân cấp';
       if($loai == 'loai_cau') $ten = 'Thêm loại cầu';
       if($loai == 'loai_ket_cau_nhip') $ten = 'Thêm loại kế cấu nhịp';
       
        return Inertia::render('CauHinh/Index', compact('cau_hinh','loai','ten'));
    }

    public function store(StoreCauHinhRequest $request)
    {
       
        $validated = $request->validated();       
        CauHinh::updateOrCreate(['id' => $validated['id']], $validated);
    }
}
