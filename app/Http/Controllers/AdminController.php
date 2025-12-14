<?php

namespace App\Http\Controllers;

use App\Models\Station;
use App\Models\User;
use Illuminate\Http\Request;
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
     * Display edit station form
     */
    public function editStation($id): View
    {
        $station = Station::with('host')->findOrFail($id);
        $owners = User::where('role', 'warga')->get();
        return view('admin.edit-station', compact('station', 'owners'));
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
     * Display edit user form
     */
    public function editUser($id): View
    {
        $user = User::findOrFail($id);
        return view('admin.edit-user', compact('user'));
    }
}
