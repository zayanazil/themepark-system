<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ad;

class AdController extends Controller
{
    public function index() {
        $ads = Ad::all();
        return view('admin.ads', compact('ads'));
    }

    public function store(Request $request) {
        Ad::create($request->validate([
            'title' => 'required',
            'content' => 'required',
            'image_url' => 'nullable'
        ]));
        return back()->with('success', 'Ad created');
    }
}
