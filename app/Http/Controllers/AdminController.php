<?php

namespace App\Http\Controllers;

use App\Models\Port;
use App\Models\Station;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AdminController extends Controller
{
    /**
     * Display admin dashboard
     */
    public function index(): View
    {
        // Get total users count
        $totalUsers = User::count();
        
        // Get active stations count
        $activeStations = Station::where('is_active', true)->count();
        
        // Get recent registered users (last 10)
        $recentUsers = User::latest('created_at')
            ->take(10)
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                    'created_at' => $user->created_at->format('d M Y'),
                    'initials' => strtoupper(substr($user->name, 0, 2)),
                ];
            });
        
        // Get active stations list
        $stations = Station::with('host:id,name')
            ->withCount('ports')
            ->where('is_active', true)
            ->take(10)
            ->get()
            ->map(function ($station) {
                return [
                    'id' => $station->id,
                    'name' => $station->name,
                    'address' => $station->address,
                    'ports_count' => $station->ports_count,
                    'initials' => strtoupper(substr($station->name, 0, 2)),
                ];
            });
        
        return view('admin.dashboard', compact('totalUsers', 'activeStations', 'recentUsers', 'stations'));
    }

    /**
     * Display add station page
     */
    public function addStation(): View
    {
        $stations = Station::with('host:id,name')->latest()->get();
        return view('admin.add-station', compact('stations'));
    }

    /**
     * Display create station form
     */
    public function createStation(): View
    {
        $owners = User::where('role', 'warga')->get();
        return view('admin.create-station', compact('owners'));
    }

    /**
     * Store new station
     */
    public function storeStation(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'user_id' => 'required|exists:users,id',
            'num_ports' => 'required|integer|min:1|max:10',
            'power_kw' => 'required|integer|min:1',
            'price_per_kwh' => 'required|numeric|min:0',
        ]);

        // Create station
        $station = Station::create([
            'name' => $validated['name'],
            'address' => $validated['address'],
            'latitude' => $validated['latitude'],
            'longitude' => $validated['longitude'],
            'user_id' => $validated['user_id'],
            'is_active' => $request->has('is_active'), // Handle checkbox
        ]);

        // Create ports for this station
        $numPorts = $validated['num_ports'];
        for ($i = 1; $i <= $numPorts; $i++) {
            Port::create([
                'station_id' => $station->id,
                'name' => "Port " . chr(64 + $i), // Port A, Port B, Port C, etc.
                'type' => $validated['power_kw'] >= 100 ? 'Fast Charging' : 'Regular Charging',
                'power_kw' => $validated['power_kw'],
                'price_per_kwh' => $validated['price_per_kwh'],
                'status' => 'available',
            ]);
        }

        return redirect()->route('admin.add-station')
            ->with('success', "Station '{$station->name}' berhasil ditambahkan dengan {$numPorts} ports");
    }

    /**
     * Display edit station form
     */
    public function editStation($id): View
    {
        $station = Station::with('host')->findOrFail($id);
        $owners = User::where('role', 'warga')->get();
        return view('admin.edit-station', compact('station', 'owners'));
    }

    /**
     * Update station
     */
    public function updateStation(Request $request, $id)
    {
        $station = Station::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'user_id' => 'required|exists:users,id',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $station->update($validated);

        return redirect()->route('admin.add-station')
            ->with('success', 'Station berhasil diupdate');
    }

    /**
     * Delete station
     */
    public function deleteStation($id)
    {
        $station = Station::findOrFail($id);
        $station->delete();

        return redirect()->route('admin.add-station')
            ->with('success', 'Station berhasil dihapus');
    }

    /**
     * Display user management page
     */
    public function userManagement(): View
    {
        $users = User::where('role', 'warga')
            ->latest('created_at')
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'username' => $user->username ?? '-',
                    'email' => $user->email,
                    'created_at' => $user->created_at->format('Y-m-d'),
                ];
            });
        
        return view('admin.user-management', compact('users'));
    }

    /**
     * Display create user form
     */
    public function createUser(): View
    {
        return view('admin.create-user');
    }

    /**
     * Store new user
     */
    public function storeUser(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6',
            'phone' => 'nullable|string',
            'phone_number' => 'nullable|string',
            'address' => 'nullable|string',
            'role' => 'required|in:admin,warga,driver',
            'is_host' => 'boolean',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['is_host'] = $request->has('is_host');

        User::create($validated);

        return redirect()->route('admin.user-management')
            ->with('success', 'User berhasil ditambahkan');
    }

    /**
     * Display edit user form
     */
    public function editUser($id): View
    {
        $user = User::findOrFail($id);
        return view('admin.edit-user', compact('user'));
    }

    /**
     * Update user
     */
    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,'.$id,
            'email' => 'required|email|unique:users,email,'.$id,
            'password' => 'nullable|string|min:6',
            'phone' => 'nullable|string',
            'phone_number' => 'nullable|string',
            'address' => 'nullable|string',
            'role' => 'required|in:admin,warga,driver',
            'is_host' => 'boolean',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $validated['is_host'] = $request->has('is_host');

        $user->update($validated);

        return redirect()->route('admin.user-management')
            ->with('success', 'User berhasil diupdate');
    }

    /**
     * Delete user
     */
    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('admin.user-management')
            ->with('success', 'User berhasil dihapus');
    }
}
