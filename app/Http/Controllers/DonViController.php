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
        $don_vi = DonVi::query();
        if($request->filled('search')){
            $don_vi->where('ten', 'like', '%'.$request->search.'%');
        }
        $don_vi = $don_vi->paginate(15);
        return Inertia::render('DonVi/Index', compact('don_vi'));
    }

    public function store(StoreDonViRequest $request)
    {
        $validated = $request->validated();
        DonVi::updateOrCreate(['id' => $validated['id']],$validated);
    }
}
