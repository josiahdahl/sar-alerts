<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class DataTidesMailable extends Mailable
{
    use Queueable, SerializesModels;

    public $message;
    public $date;
    public $subject;

    /**
     * Create a new message instance.
     *
     * @param $data
     */
    public function __construct($data)
    {
        $this->message = $data['message'];
        $this->date = $data['date'];
        $this->subject = $data['subject'];
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.data-tides')
            ->subject($this->subject)
            ->with([
                'message' => $this->message,
                'date' => $this->date,
            ]);
    }
}
