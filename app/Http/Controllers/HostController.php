<?php

namespace App\Http\Controllers;

use App\Models\Station;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class HostController extends Controller
{
    public function index(): View
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $stations = $user->stations()->with('ports')->latest()->get();

        return view('owner.dashboard', compact('stations'));
    }

    public function create(): View
    {
        return view('owner.create-station');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:255'],
            'latitude' => ['nullable', 'numeric'],
            'longitude' => ['nullable', 'numeric'],
            'google_maps_link' => ['nullable', 'string', 'max:1024'],
        ]);

        $lat = $validated['latitude'] ?? null;
        $lng = $validated['longitude'] ?? null;

        if ((!$lat || !$lng) && !empty($validated['google_maps_link'])) {
            if (preg_match('/@(-?\d+\.\d+),(-?\d+\.\d+)/', $validated['google_maps_link'], $m)) {
                $lat = $lat ?: $m[1];
                $lng = $lng ?: $m[2];
            }
        }

        if ($lat === null || $lng === null) {
            return back()
                ->withInput()
                ->withErrors(['latitude' => 'Harap isi Latitude & Longitude atau sertakan Google Maps link dengan koordinat (@lat,lng).']);
        }

        Station::create([
            'user_id' => Auth::id(),
            'name' => $validated['name'],
            'address' => $validated['address'] ?? null,
            'latitude' => $lat,
            'longitude' => $lng,
            'operational_hours' => '00:00-23:59',
            'is_open' => true,
            'is_active' => true,
        ]);

        return redirect()->route('owner.dashboard')->with('success', 'Station berhasil dibuat');
    }
}
