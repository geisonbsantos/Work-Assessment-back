<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ReportBugMail extends Mailable
{
    use Queueable, SerializesModels;

    private $data;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(array $data)
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
        return $this
            ->subject('Relato de Problemas - Starter Pack')
            ->view('email.reportBug', [
                'description' => $this->data['description'],
                'subject' => $this->data['subject'],
                'cpf' => $this->data['cpf'],
                'email' => $this->data['email'],
                'name' => $this->data['name'],
                'system' => $this->data['system'],
            ]);
    }
}
