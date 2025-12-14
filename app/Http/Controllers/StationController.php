<?php

namespace App\Http\Controllers;

use App\Models\Station;
use Illuminate\Contracts\View\View;

class StationController extends Controller
{
    public function index(): View
    {
        $query = Station::query()
            ->with(['host:id,name', 'ports'])
            ->select('id', 'user_id', 'name', 'address', 'latitude', 'longitude', 'operational_hours', 'is_active')
            ->where('is_active', true);

        // Handle search query
        if ($search = request('search')) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('address', 'like', "%{$search}%");
            });
        }

        $stations = $query->get();

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
