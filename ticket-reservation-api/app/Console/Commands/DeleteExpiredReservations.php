<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Reservation;
use Carbon\Carbon;

class DeleteExpiredReservations extends Command
{
    protected $signature = 'reservations:delete-expired';
    protected $description = 'Delete expired reservations';

    public function handle()
    {
        // Süresi dolmuş rezervasyonları bul
        $expiredReservations = Reservation::where('expires_at', '<', Carbon::now())->get();

        foreach ($expiredReservations as $reservation) {
            // Koltukları tekrar "available" yap
            foreach ($reservation->items as $item) {
                $item->seat->update(['status' => 'available']);
                $item->delete();
            }

            // Rezervasyonu sil
            $reservation->delete();
        }

        $this->info('Expired reservations deleted successfully.');
    }
}
