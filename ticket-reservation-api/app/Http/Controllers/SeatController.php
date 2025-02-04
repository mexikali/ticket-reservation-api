<?php

namespace App\Http\Controllers;

use App\Models\Seat;
use App\Models\Event;
use App\Models\Venue;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SeatController extends Controller
{
    // Tüm koltukları listele
    public function index()
    {
        return response()->json(Seat::all(), 200);
    }

    // Tek bir koltuğu getir
    public function show($id)
    {
        $seat = Seat::find($id);
        if (!$seat) {
            return response()->json(['error' => 'Seat not found'], 404);
        }
        return response()->json($seat, 200);
    }

    // Yeni koltuk ekle (Admin Only)
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'venue_id' => 'required|exists:venues,id',
                'section' => 'nullable|string|max:255',
                'row' => 'required|string|max:10',
                'number' => 'required|integer|min:1',
                'status' => 'sometimes|in:available,reserved,sold',
                'price' => 'required|numeric|min:0'
            ]);

            // Mekan bilgilerini al
            $venue = Venue::find($validated['venue_id']);

            // Şu anki toplam koltuk sayısını bul
            $currentSeats = Seat::where('venue_id', $venue->id)->count();

            // Kapasiteyi aşıyor mu kontrol et
            if ($currentSeats >= $venue->capacity) {
                return response()->json(['error' => 'Venue capacity exceeded!'], 400);
            }

            $seat = Seat::create($validated);
            return response()->json($seat, 201);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to create seat', 'message' => $e->getMessage()], 400);
        }
    }

    // Koltuğu güncelle (Admin Only)
    public function update(Request $request, $id)
    {
        try {
            $seat = Seat::find($id);
            if (!$seat) {
                return response()->json(['error' => 'Seat not found'], 404);
            }

            $validated = $request->validate([
                'venue_id' => 'sometimes|exists:venues,id',
                'section' => 'sometimes|string|max:255',
                'row' => 'sometimes|string|max:10',
                'number' => 'sometimes|integer|min:1',
                'status' => 'sometimes|in:available,reserved,sold',
                'price' => 'sometimes|numeric|min:0'
            ]);

            $seat->update($validated);
            return response()->json($seat, 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to update seat', 'message' => $e->getMessage()], 400);
        }
    }

    // Koltuğu sil (Admin Only)
    public function destroy($id)
    {
        try {
            $seat = Seat::find($id);
            if (!$seat) {
                return response()->json(['error' => 'Seat not found'], 404);
            }

            $seat->delete();
            return response()->json(['message' => 'Seat deleted successfully'], 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to delete seat', 'message' => $e->getMessage()], 500);
        }
    }

    // Belirtilen event ID'ye göre etkinliğin gerçekleştiği mekandaki tüm koltukları getir
    public function getSeatsByEvent($id)
    {
        $event = Event::find($id);
        if (!$event) {
            return response()->json(['error' => 'Event not found'], 404);
        }

        $seats = Seat::where('venue_id', $event->venue_id)->get();
        return response()->json($seats, 200);
    }

    // Belirtilen venue ID'ye göre o mekandaki tüm koltukları getir
    public function getSeatsByVenue($id)
    {
        $venue = Venue::find($id);
        if (!$venue) {
            return response()->json(['error' => 'Venue not found'], 404);
        }

        $seats = Seat::where('venue_id', $id)->get();
        return response()->json($seats, 200);
    }

    // Belirtilen koltukları geçici olarak rezerve et (block)
    public function blockSeats(Request $request)
    {
        try {
            // Koltuk ID'lerini al
            $validated = $request->validate([
                'seat_ids' => 'required|array|min:1',
                'seat_ids.*' => 'exists:seats,id'
            ]);

            $seatIds = $validated['seat_ids'];

            // Seçili koltukların hepsi "available" mi kontrol et
            $availableSeats = Seat::whereIn('id', $seatIds)
                ->where('status', 'available')
                ->count();

            if ($availableSeats !== count($seatIds)) {
                return response()->json(['error' => 'Some seats are not available. None were blocked.'], 400);
            }

            // Eğer hepsi "available" ise, hepsini "reserved" yap
            Seat::whereIn('id', $seatIds)->update(['status' => 'reserved']);

            return response()->json(['message' => 'Seats successfully blocked'], 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to block seats', 'message' => $e->getMessage()], 500);
        }
    }

    // Rezerve edilmiş koltukları serbest bırak (release)
    public function releaseSeats(Request $request)
    {
        try {
            // Koltuk ID'lerini al ve doğrula
            $validated = $request->validate([
                'seat_ids' => 'required|array|min:1',
                'seat_ids.*' => 'exists:seats,id'
            ]);

            $seatIds = $validated['seat_ids'];

            // Seçili koltukların hepsi "reserved" mi kontrol et
            $reservedSeats = Seat::whereIn('id', $seatIds)
                ->where('status', 'reserved')
                ->count();

            if ($reservedSeats !== count($seatIds)) {
                return response()->json(['error' => 'Some seats are not reserved. None were released.'], 400);
            }

            // Eğer hepsi "reserved" ise, hepsini "available" yap
            Seat::whereIn('id', $seatIds)->update(['status' => 'available']);

            return response()->json(['message' => 'Seats successfully released'], 200);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to release seats', 'message' => $e->getMessage()], 500);
        }
    }


}
