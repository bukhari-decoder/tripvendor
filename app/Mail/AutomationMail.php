<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AutomationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $sendEmailList;
    public $htmlCode;
    public $url;

    /**
     * Create a new message instance.
     */
    public function __construct($sendEmailList, $htmlCode)
    {
        $this->sendEmailList = $sendEmailList;
        $this->htmlCode = $htmlCode;
        $this->url = route('automationTrack', $sendEmailList->id);
    }

    public function build()
    {
        return $this->view('email.automation_email')
            ->subject($this->sendEmailList->subject)
            ->with([
                'htmlCode' => $this->htmlCode,
                'emailTrack' => $this->url,
            ])
            ->from($this->sendEmailList->sender_address, $this->sendEmailList->sender_name)
            ->replyTo($this->sendEmailList->reply_to);
    }
}
