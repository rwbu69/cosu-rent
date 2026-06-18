<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

use App\Models\Booking;
use App\Mail\ReturnReminder;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

#[Signature('app:send-return-reminders')]
#[Description('Sends an email reminder for costumes due tomorrow.')]
class SendReturnReminders extends Command
{
    /**
     * Execute the console command.
     */
    public function handle()
    {
        $tomorrow = Carbon::tomorrow()->toDateString();
        
        $bookings = Booking::with('user')
            ->where('end_date', $tomorrow)
            ->where('status', '!=', 'Returned')
            ->get();

        foreach ($bookings as $booking) {
            Mail::to($booking->user->email)->queue(new ReturnReminder($booking));
        }

        $this->info("Sent reminders to " . $bookings->count() . " users.");
    }
}
