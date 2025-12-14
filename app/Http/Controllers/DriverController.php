<?php

namespace App\Http\Controllers;

use App\Models\Station;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DriverController extends Controller
{
    /**
     * Display driver dashboard with nearby stations
     */
    public function index(): View
    {
        $user = auth()->user();
        
        // Get all active stations with their ports
        $stations = Station::with(['ports'])
            ->where('is_active', true)
            ->where('is_open', true)
            ->get()
            ->map(function ($station) {
                // Calculate available ports
                $availablePorts = $station->ports()->where('status', 'available')->count();
                $totalPorts = $station->ports()->count();
                
                // Determine status color based on availability
                $statusColor = 'green';
                if ($availablePorts == 0) {
                    $statusColor = 'red';
                } elseif ($availablePorts <= $totalPorts / 3) {
                    $statusColor = 'yellow';
                }
                
                return [
                    'id' => $station->id,
                    'name' => $station->name,
                    'address' => $station->address,
                    'latitude' => $station->latitude,
                    'longitude' => $station->longitude,
                    'operational_hours' => $station->operational_hours,
                    'amenities' => $station->amenities ?? [],
                    'available_ports' => $availablePorts,
                    'total_ports' => $totalPorts,
                    'status_color' => $statusColor,
                    'average_rating' => round($station->averageRating(), 1),
                ];
            });
        
        // Count total active stations
        $totalStations = $stations->count();
        
        // Calculate total available ports across all stations
        $totalAvailablePorts = $stations->sum('available_ports');
        
        // Find nearest station (for now, just use the first one as placeholder)
        $nearestDistance = $stations->isNotEmpty() ? '1.5 KM' : '-';
        
        // Count pending reservations for current user
        $pendingReservations = Transaction::where('user_id', $user->id)
            ->where('payment_status', 'pending')
            ->count();
        
        return view('driver.dashboard', compact('stations', 'totalStations', 'totalAvailablePorts', 'nearestDistance', 'pendingReservations'));
    }

    /**
     * Display driver reservations
     */
    public function reservations(): View
    {
        $user = auth()->user();
        
        // Get the most recent pending or active transaction
        $reservation = Transaction::with(['station', 'port'])
            ->where('user_id', $user->id)
            ->whereIn('payment_status', ['pending', 'paid'])
            ->latest('created_at')
            ->first();
        
        return view('driver.reservations', compact('reservation'));
    }

    /**
     * Display invoice details
     */
    public function invoice(): View
    {
        $user = auth()->user();
        
        // Get the most recent transaction or specific transaction
        $transaction = Transaction::with(['station', 'port'])
            ->where('user_id', $user->id)
            ->latest('created_at')
            ->first();
        
        return view('driver.invoice', compact('transaction'));
    }

    /**
     * Display station detail for driver
     */
    public function showStation($id): View
    {
        $station = Station::query()
            ->with(['host:id,name', 'ports', 'reviews.user'])
            ->where('is_active', true)
            ->findOrFail($id);
        
        // Calculate additional stats
        $station->available_ports_count = $station->ports()->where('status', 'available')->count();
        $station->average_rating = $station->averageRating();
        
        return view('stations.show', compact('station'));
    }

    /**
     * Display driver profile page (view only with booking history)
     */
    public function profile(): View
    {
        $user = auth()->user();
        
        // Get transactions (new) instead of bookings (old)
        $transactions = $user->transactions()
            ->with('station')
            ->latest()
            ->get();
        
        // Keep bookings for backward compatibility if needed
        $bookings = $user->bookings()->with('station')->latest()->get();
        
        return view('driver.profile', compact('user', 'bookings', 'transactions'));
    }

    /**
     * Show edit profile form
     */
    public function editProfile(): View
    {
        $user = auth()->user();
        return view('driver.edit-profile', compact('user'));
    }

    /**
     * Update driver profile information
     */
    public function updateProfile(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . auth()->id(),
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
        ]);

        $user = auth()->user();
        $user->update($validated);

        return redirect()->route('driver.profile.edit')->with('success', 'Profile updated successfully!');
    }

    /**
     * Show password change form
     */
    public function showPasswordForm(): View
    {
        return view('driver.change-password');
    }

    /**
     * Update driver password
     */
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        $user = auth()->user();

        // Verify current password
        if (!password_verify($validated['current_password'], $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        // Update password
        $user->update([
            'password' => bcrypt($validated['new_password']),
        ]);

        return redirect()->route('driver.profile.password')->with('success', 'Password changed successfully!');
    }
}
