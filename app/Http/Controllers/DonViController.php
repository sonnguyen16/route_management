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
        $obj = DonVi::query();;
        if($request->filled('search')){
            $obj = $obj->where('ten', 'like', '%'.$request->search.'%');
        }
        $obj = $obj->where('loai', 1);
        $obj = $obj->paginate(15);
        return Inertia::render('DonVi/Index', compact('obj'));
    }

    public function store(StoreDonViRequest $request)
    {
        $validated = $request->validated();
        DonVi::updateOrCreate(['id' => $validated['id']],$validated);
    }
}
