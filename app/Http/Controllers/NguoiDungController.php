<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreNguoiDungRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class NguoiDungController extends Controller
{
    public function index(Request $request)
    {
        $nguoi_dung = User::query();
        if($request->filled('search')){
            $nguoi_dung = User::where('name', 'like', '%'.$request->search.'%')
                ->orWhere('email', 'like', '%'.$request->search.'%');
        }
        $nguoi_dung = $nguoi_dung->paginate(100);
        return Inertia::render('NguoiDung/Index', compact('nguoi_dung'));
    }

    public function store(StoreNguoiDungRequest $request)
    {
        $validated = $request->validated();
        if($validated['password']){
            $validated['password'] = bcrypt($validated['password']);
        }else{
            unset($validated['password']);
        }
        User::updateOrCreate(['id' => $validated['id']], $validated);
    }
}
