<?php

namespace App\Http\Controllers;

use App\Models\Station;
use Illuminate\Contracts\View\View;

class StationController extends Controller
{
    public function index(): View
    {
        $stations = Station::query()
            ->with(['host:id,name', 'ports'])
            ->select('id', 'user_id', 'name', 'address', 'latitude', 'longitude', 'operational_hours', 'is_active')
            ->where('is_active', true)
            ->get();

        return view('stations.index', compact('stations'));
    }

    public function show($id): View
    {
        $station = Station::query()
            ->with(['host:id,name', 'ports', 'reviews.user:id,name'])
            ->where('is_active', true)
            ->findOrFail($id);

        return view('stations.show', compact('station'));
    }
}
