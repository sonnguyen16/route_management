<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\TuyenDuong;

class TaiLieuController extends Controller
{
    public function index()
    {
        return redirect()->route('unisharp.lfm.show');
    }
}
