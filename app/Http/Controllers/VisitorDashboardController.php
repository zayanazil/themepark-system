<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ad;
use App\Models\MapLocation;

class VisitorDashboardController extends Controller
{
    public function index()
    {
        $ads = Ad::latest()->get();
        $locations = MapLocation::all();

        return view('visitor.dashboard', compact('ads', 'locations'));
    }
}
