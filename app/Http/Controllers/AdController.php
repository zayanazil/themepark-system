<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ad;

class AdController extends Controller
{
    public function index() {
        $ads = Ad::latest()->get(); // Show newest first
        return view('admin.ads', compact('ads'));
    }

    public function store(Request $request) {
        Ad::create($request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image_url' => 'nullable|url'
        ]));
        return back()->with('success', 'Advertisement created successfully!');
    }

    public function destroy($id)
    {
        try {
            $ad = Ad::findOrFail($id);
            $ad->delete();
            return back()->with('success', 'Advertisement deleted successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete advertisement: ' . $e->getMessage());
        }
    }
}