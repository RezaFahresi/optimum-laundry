<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Schedule;

class ScheduleReminder extends Mailable
{
    use Queueable, SerializesModels;

    public $schedule;

    /**
     * Create a new message instance.
     */
    public function __construct(Schedule $schedule)
    {
        $this->schedule = $schedule;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Pengingat Jadwal Rutin')
            ->view('emails.schedule_reminder')
            ->with([
                'schedule' => $this->schedule
            ]);
    }
}
