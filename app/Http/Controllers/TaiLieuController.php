<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TaiLieu;
use Illuminate\Support\Facades\Storage;

class TaiLieuController extends Controller
{
    public function delete(Request $request)
    {
        $tai_lieu = TaiLieu::find($request->id);
        $path = $tai_lieu->file;
        Storage::disk('public')->delete($path);
        $tai_lieu->delete();
    }
}
