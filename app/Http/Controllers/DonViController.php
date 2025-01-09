<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Requests\StoreDonViRequest;
use App\Models\DonVi;


class DonViController extends Controller
{
    public function index(Request $request)
    {
        $obj = DonVi::where('isdelete',0)->with(['tai_lieu']);
        if($request->filled('search')){
            $obj = $obj->where('ten', 'like', '%'.$request->search.'%');
        }
        $obj = $obj->where('loai', 1);
        $obj = $obj->paginate(20);
        if(isset($request->page)) {
            $stt = $request->page*20 - 19;
           } else {
            $stt = 1;
           }
        return Inertia::render('DonVi/Index', compact('obj','stt'));
    }

    public function store(StoreDonViRequest $request)
    {
        $validated = $request->validated();
        DonVi::updateOrCreate(['id' => $validated['id']],$validated);
    }
    public function delete(Request $request)
    {
       $obj = DonVi::find($request->id);
        $obj->isdelete = 1;
        $obj->save();
    }
}
