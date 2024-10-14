<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Http\Requests\StoreDonViRequest;
use App\Models\DonVi;

class DonViController extends Controller
{
    public function index()
    {
        $don_vi = DonVi::paginate(15);
        return Inertia::render('DonVi/Index', compact('don_vi'));
    }

    public function store(StoreDonViRequest $request)
    {
        $validated = $request->validated();
        DonVi::updateOrCreate(['id' => $validated['id']],$validated);
    }
}
