<?php

namespace App\Http\Controllers;

use App\Models\Station;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OwnerController extends Controller
{
    /**
     * Display the owner analytics dashboard
     */
    public function index(): View
    {
        $user = auth()->user();
        
        // Get all stations owned by the current user
        $stations = Station::where('user_id', $user->id)
            ->with(['ports'])
            ->get()
            ->map(function ($station) {
                return [
                    'id' => $station->id,
                    'name' => $station->name,
                    'address' => $station->address,
                    'selected' => false, // You can implement selection logic later
                ];
            });

        // Get the first station for display (or selected station)
        $selectedStation = Station::where('user_id', $user->id)->first();

        // Statistics for the selected station
        $stats = [];
        if ($selectedStation) {
            // Calculate today's revenue
            $todayRevenue = Transaction::where('station_id', $selectedStation->id)
                ->whereDate('date', today())
                ->where('payment_status', 'paid')
                ->sum('total_price');

            // Count completed transactions today
            $completedToday = Transaction::where('station_id', $selectedStation->id)
                ->whereDate('date', today())
                ->where('payment_status', 'paid')
                ->count();

            // Get available ports count
            $availablePorts = $selectedStation->ports()->where('status', 'available')->count();
            $totalPorts = $selectedStation->ports()->count();

            $stats = [
                'revenue_today' => 'Rp. ' . number_format($todayRevenue, 0, ',', '.'),
                'total_activity' => $completedToday . ' Completed',
                'available_ports' => $availablePorts . '/' . $totalPorts
            ];
        }

        // Get charging history from database
        $chargingHistory = [];
        if ($selectedStation) {
            $chargingHistory = Transaction::where('station_id', $selectedStation->id)
                ->with(['user', 'port'])
                ->latest('date')
                ->latest('start_time')
                ->limit(10)
                ->get()
                ->map(function ($transaction) {
                    // Map payment status to display status
                    $statusMap = [
                        'pending' => ['status' => 'pending', 'text' => 'Pending'],
                        'paid' => ['status' => 'succeeded', 'text' => 'Succeeded'],
                        'cancelled' => ['status' => 'cancelled', 'text' => 'Cancelled'],
                    ];

                    $status = $statusMap[$transaction->payment_status] ?? ['status' => 'pending', 'text' => 'Pending'];

                    return [
                        'transaction_id' => $transaction->transaction_code,
                        'user' => $transaction->user->username ?? $transaction->user->name,
                        'license_plate' => 'B ' . rand(1000, 9999) . ' XYZ', // Placeholder, add to transaction table if needed
                        'duration' => $transaction->duration_minutes ? $transaction->duration_minutes . ' mnt' : '-',
                        'price' => 'Rp. ' . number_format($transaction->total_price, 0, ',', '.'),
                        'status' => $status['status'],
                        'status_text' => $status['text'],
                    ];
                })
                ->toArray();
        }

        return view('owner.dashboard', compact('stations', 'stats', 'chargingHistory'));
    }

    /**
     * Show transaction detail page
     */
    public function showTransactionDetail($id): View
    {
        // Find transaction by transaction_code from database
        $transactionModel = Transaction::where('transaction_code', $id)
            ->with(['user', 'port', 'station', 'review'])
            ->firstOrFail();

        // Map payment status to display status
        $statusMap = [
            'pending' => ['status' => 'pending', 'text' => 'Pending'],
            'paid' => ['status' => 'succeeded', 'text' => 'Succeeded'],
            'cancelled' => ['status' => 'cancelled', 'text' => 'Cancelled'],
        ];

        $status = $statusMap[$transactionModel->payment_status] ?? ['status' => 'pending', 'text' => 'Pending'];

        // Build transaction array for the view
        $transaction = [
            'transaction_id' => $transactionModel->transaction_code,
            'user' => $transactionModel->user->name,
            'license_plate' => 'B ' . rand(1000, 9999) . ' XYZ', // Placeholder
            'duration' => $transactionModel->duration_minutes ? $transactionModel->duration_minutes . ' mnt' : '-',
            'price' => 'Rp. ' . number_format($transactionModel->total_price, 0, ',', '.'),
            'status' => $status['status'],
            'status_text' => $status['text'],
            'rate' => $transactionModel->port ? 'Rp ' . number_format($transactionModel->port->price_per_kwh, 0, ',', '.') . '/kWh' : '-',
            'date' => $transactionModel->date->format('d/m/Y'),
            'time' => $transactionModel->start_time->format('H:i'),
        ];

        // Add review data if exists
        if ($transactionModel->review) {
            $transaction['rating'] = $transactionModel->review->rating;
            $transaction['review'] = $transactionModel->review->comment;
            $transaction['review_time'] = $transactionModel->review->created_at->diffForHumans();
        } else {
            $transaction['rating'] = 0;
            $transaction['review'] = 'Belum ada review';
            $transaction['review_time'] = '-';
        }

        return view('owner.transaction-detail', compact('transaction'));
    }

    /**
     * Show owner profile with station list
     */
    public function profile(): View
    {
        // Get current user
        $user = auth()->user();

        // Get owned stations from database
        $ownedStations = Station::where('user_id', $user->id)
            ->with(['ports', 'transactions', 'reviews'])
            ->get()
            ->map(function ($station) {
                // Calculate total revenue
                $totalRevenue = $station->transactions()
                    ->where('payment_status', 'paid')
                    ->sum('total_price');

                // Count transactions
                $totalTransactions = $station->transactions()->count();

                // Get available ports
                $availablePorts = $station->ports()->where('status', 'available')->count();
                $totalPorts = $station->ports()->count();

                // Calculate average rating
                $averageRating = $station->reviews()->avg('rating') ?? 0;

                return [
                    'id' => $station->id,
                    'name' => $station->name,
                    'address' => $station->address,
                    'total_ports' => $totalPorts,
                    'available_ports' => $availablePorts,
                    'status' => $station->is_active ? 'active' : 'inactive',
                    'total_revenue' => 'Rp. ' . number_format($totalRevenue, 0, ',', '.'),
                    'total_transactions' => $totalTransactions,
                    'rating' => round($averageRating, 1),
                ];
            })
            ->toArray();

        return view('owner.profile', compact('user', 'ownedStations'));
    }
}
