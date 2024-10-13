<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class NguoiDungController extends Controller
{
    public function index()
    {
        $nguoi_dung = User::paginate(15);
        return Inertia::render('NguoiDung/Index', compact('nguoi_dung'));
    }
}
