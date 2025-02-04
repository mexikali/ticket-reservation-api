<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\ReservationItem;
use App\Models\Seat;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ReservationController extends Controller
{
    // Tüm rezervasyonları listele
    public function index()
    {
        $reservations = Reservation::with(['user', 'event', 'items.seat'])->get();
        return response()->json($reservations);
    }

    // Tekil rezervasyon bilgisi
    public function show($id)
    {
        $reservation = Reservation::with(['user', 'event', 'items.seat'])->find($id);

        if (!$reservation) {
            return response()->json(['error' => 'Reservation not found'], 404);
        }

        return response()->json($reservation);
    }

    // Yeni rezervasyon oluştur
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'event_id' => 'required|exists:events,id',
            'seat_ids' => 'required|array|min:1',
            'seat_ids.*' => 'exists:seats,id'
        ]);

        DB::beginTransaction();
        try {
            // Koltukların müsait olup olmadığını kontrol et
            $reservedSeats = Seat::whereIn('id', $validated['seat_ids'])
                ->where('status', '!=', 'available')
                ->count();

            if ($reservedSeats > 0) {
                return response()->json(['error' => 'Some seats are already reserved'], 400);
            }

            // Rezervasyon oluştur
            $reservation = Reservation::create([
                'user_id' => $validated['user_id'],
                'event_id' => $validated['event_id'],
                'status' => 'pending',
                'total_amount' => 0, // Aşağıda hesaplanacak
                'expires_at' => Carbon::now()->addMinutes(15),
            ]);

            $totalAmount = 0;

            // Rezervasyon öğelerini ekle ve koltukları "reserved" yap
            foreach ($validated['seat_ids'] as $seatId) {
                $seat = Seat::find($seatId);
                ReservationItem::create([
                    'reservation_id' => $reservation->id,
                    'seat_id' => $seat->id,
                    'price' => $seat->price
                ]);

                $seat->update(['status' => 'reserved']);
                $totalAmount += $seat->price;
            }

            // Toplam ücreti güncelle
            $reservation->update(['total_amount' => $totalAmount]);

            DB::commit();
            return response()->json($reservation, 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Reservation failed', 'message' => $e->getMessage()], 500);
        }
    }

    // Rezervasyonu onayla
    public function confirm($id)
    {
        $reservation = Reservation::with('items.seat')->find($id);

        if (!$reservation) {
            return response()->json(['error' => 'Reservation not found'], 404);
        }

        if ($reservation->isExpired()) {
            return response()->json(['error' => 'Reservation expired'], 400);
        }

        // Koltukları "sold" yap
        foreach ($reservation->items as $item) {
            $item->seat->update(['status' => 'sold']);
        }

        $reservation->update(['status' => 'confirmed']);
        return response()->json(['message' => 'Reservation confirmed']);
    }

    // Rezervasyonu iptal et
    public function destroy($id)
    {
        $reservation = Reservation::with('items.seat')->find($id);

        if (!$reservation) {
            return response()->json(['error' => 'Reservation not found'], 404);
        }

        DB::beginTransaction();
        try {
            // Koltukları tekrar "available" yap
            foreach ($reservation->items as $item) {
                $item->seat->update(['status' => 'available']);
                $item->delete();
            }

            // Rezervasyonu sil
            $reservation->delete();
            DB::commit();

            return response()->json(['message' => 'Reservation cancelled and seats released']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Cancellation failed', 'message' => $e->getMessage()], 500);
        }
    }
}

