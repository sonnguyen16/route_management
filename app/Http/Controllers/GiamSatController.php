<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\GiamSat;
use App\Http\Requests\StoreGiamSatRequest;

class GiamSatController extends Controller
{
    public function index()
    {
        $giam_sat = GiamSat::with(['tuyen_duong', 'don_vi'])->paginate(15);
        return Inertia::render('GiamSat/Index', compact('giam_sat'));
    }

    public function store(StoreGiamSatRequest $request)
    {
        $validated = $request->validated();
        unset($validated['tai_lieu']);
        GiamSat::updateOrCreate(['id' => $validated['id']],$validated);
    }
}
