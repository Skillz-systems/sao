<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ProjectPaymentRequest extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

     public $data;
     public function __construct($data)
     {
         $this->data = $data;
     }


    /**
     * Build the message.
     *
     * @return $this
     */
     public function build()
     {
        return $this->from('saoenergy@gmail.com',  'SAOEnergy')->subject("Payment request")->view('emails.projectpaymentrequest')->with('data', $this->data);
     }
}
