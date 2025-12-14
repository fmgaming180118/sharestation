<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Station;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::with('station')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return view('bookings.index', compact('bookings'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'station_id' => ['required', 'integer', 'exists:stations,id'],
            'start_at' => ['nullable', 'date'],
            'end_at' => ['nullable', 'date', 'after_or_equal:start_at'],
            'price_per_kwh' => ['nullable', 'numeric', 'min:0'],
            'energy_kwh' => ['nullable', 'numeric', 'min:0'],
        ]);

        $station = Station::findOrFail($validated['station_id']);

        Booking::createForUser($request->user(), $station, $validated);

        return redirect('/');
    }
}
