<?php

namespace App\Mail;

use App\Models\Newsletter;
use App\Models\Subscriber;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewsletterMail extends Mailable
{
    use Queueable, SerializesModels;

    public $newsletter;
    public $subscriber;

    /**
     * Create a new message instance.
     */
    public function __construct(Newsletter $newsletter, Subscriber $subscriber = null)
    {
        $this->newsletter = $newsletter;
        $this->subscriber = $subscriber;
    }

    public function build()
    {
        $html = $this->newsletter->html;
        if ($this->subscriber) {
            $html = str_replace("[mail]", $this->subscriber->email, $html);
            $html = str_replace("[unsubscribe_link]", route('unsubscribe.newsletter'), $html);
        }
        return $this->subject($this->newsletter->subject)->html($html);
    }
}
