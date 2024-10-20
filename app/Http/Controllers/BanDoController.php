<?php

namespace App\Http\Controllers;
use App\Models\TuyenDuong;
use Inertia\Inertia;

class BanDoController extends Controller
{
    public function index()
    {
        $tuyen_duong = TuyenDuong::with(['diem_dau_xa','diem_cuoi_xa','huyen', 'tai_lieu', 'don_vi', 'toa_do'])->get();
        return Inertia::render('Map/Index', compact('tuyen_duong'));
    }
}
