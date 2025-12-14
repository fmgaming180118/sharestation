@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">My Bookings</h1>

    @if($bookings->isEmpty())
        <div class="bg-gray-100 p-8 rounded-lg text-center">
            <p class="text-gray-600">You don't have any bookings yet.</p>
            <a href="/home" class="mt-4 inline-block bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
                Find Charging Stations
            </a>
        </div>
    @else
        <div class="grid gap-4">
            @foreach($bookings as $booking)
                <div class="bg-white shadow rounded-lg p-6">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="text-lg font-semibold">{{ $booking->station->name }}</h3>
                            <p class="text-gray-600">{{ $booking->station->address }}</p>
                            <p class="text-sm text-gray-500 mt-2">
                                Start: {{ $booking->start_at ? $booking->start_at->format('d M Y, H:i') : 'N/A' }}
                            </p>
                            <p class="text-sm text-gray-500">
                                End: {{ $booking->end_at ? $booking->end_at->format('d M Y, H:i') : 'N/A' }}
                            </p>
                        </div>
                        <div class="text-right">
                            <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold
                                @if($booking->status->value === 1) bg-yellow-100 text-yellow-800
                                @elseif($booking->status->value === 2) bg-blue-100 text-blue-800
                                @elseif($booking->status->value === 3) bg-green-100 text-green-800
                                @else bg-red-100 text-red-800
                                @endif">
                                {{ ucfirst(str_replace('_', ' ', $booking->status->name)) }}
                            </span>
                            <p class="mt-2 text-lg font-bold">Rp {{ number_format($booking->amount, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
