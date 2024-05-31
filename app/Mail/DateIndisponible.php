<?php

namespace App\Mail;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DateIndisponible extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $data = [];
    public function __construct(array $detail)
    {
        $this->data = $detail;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('eovatrano@gmail.com')
            ->subject('Date indisponible pour le ' . Carbon::parse($this->data['date_demenagement'])->locale('fr')->isoFormat('DD MMMM YYYY'))
            ->view('emails.dateIndisponible');
    }
}
