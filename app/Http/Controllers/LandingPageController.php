<?php

namespace App\Http\Controllers;

use App\Models\Station;
use Illuminate\Http\Request;

class LandingPageController extends Controller
{
    /**
     * Display landing page beranda.
     */
    public function index()
    {
        // Get total active stations
        $totalStations = Station::where('is_active', true)->count();
        
        // Get featured stations (latest 3)
        $featuredStations = Station::with('host:id,name')
            ->where('is_active', true)
            ->where('is_open', true)
            ->latest()
            ->take(3)
            ->get();
        
        return view('landingpage.beranda', compact('totalStations', 'featuredStations'));
    }

    /**
     * Display landing page inovasi.
     */
    public function inovasi()
    {
        $totalStations = Station::where('is_active', true)->count();
        return view('landingpage.inovasi', compact('totalStations'));
    }

    /**
     * Display landing page fitur utama.
     */
    public function fiturUtama()
    {
        $totalStations = Station::where('is_active', true)->count();
        return view('landingpage.fitur-utama', compact('totalStations'));
    }

    /**
     * Display landing page kontak.
     */
    public function kontak()
    {
        return view('landingpage.kontak');
    }
}
