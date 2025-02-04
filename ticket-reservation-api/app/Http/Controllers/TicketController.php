<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;

class TicketController extends Controller
{
    // Tüm biletleri listeleme
    public function index()
    {
        return response()->json(Ticket::all(), 200);
    }

    // Belirli bir bileti getirme
    public function show($id)
    {
        $ticket = Ticket::find($id);

        if (!$ticket) {
            return response()->json(['message' => 'Ticket not found'], 404);
        }

        return response()->json($ticket, 200);
    }

    // Bileti PDF olarak indirme
    public function download($id)
    {
        $ticket = Ticket::with([
            'reservation.user',
            'reservation.event.venue',
            'seat'
        ])->findOrFail($id);
    
        // PDF için gerekli verileri al
        $data = [
            'ticket_code' => $ticket->ticket_code,
            'reservation_id' => $ticket->reservation_id,
            'user' => $ticket->reservation->user,
            'event' => $ticket->reservation->event,
            'venue' => $ticket->reservation->event->venue,
            'seat' => $ticket->seat,
        ];
    
        // PDF oluştur
        $pdf = Pdf::loadView('tickets.pdf', $data);
    
        return $pdf->download("ticket_{$ticket->ticket_code}.pdf");
    }

    // Bileti başka bir kullanıcıya transfer etme
    public function transfer(Request $request, $id)
    {
        $ticket = Ticket::find($id);

        if (!$ticket) {
            return response()->json(['message' => 'Ticket not found'], 404);
        }

        if ($ticket->status !== 'valid') {
            return response()->json(['message' => 'Only valid tickets can be transferred'], 400);
        }

        $validator = Validator::make($request->all(), [
            'new_user_id' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        // Rezervasyon sahibini değiştir
        $ticket->reservation->update(['user_id' => $request->new_user_id]);

        return response()->json(['message' => 'Ticket transferred successfully'], 200);
    }
}

