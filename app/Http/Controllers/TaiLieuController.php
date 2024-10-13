<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\TuyenDuong;

class TaiLieuController extends Controller
{
    public function index()
    {
        $tuyen_duong = TuyenDuong::whereHas('tai_lieu')->with('tai_lieu')->get();
        return Inertia::render('TaiLieu/Index', compact('tuyen_duong'));
    }
}
