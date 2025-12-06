<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MapLocation;

class MapController extends Controller
{
    public function index() {
        $locations = MapLocation::all();
        return view('admin.map', compact('locations'));
    }

    public function store(Request $request) {
        MapLocation::create($request->validate([
            'name' => 'required',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'description' => 'nullable'
        ]));
        return back()->with('success', 'Location added');
    }

    public function destroy($id)
    {
        try {
            $location = MapLocation::findOrFail($id);
            $location->delete();
            return back()->with('success', 'Location removed successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to remove location: ' . $e->getMessage());
        }
    }
}