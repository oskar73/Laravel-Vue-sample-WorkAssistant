<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Snowfire\Beautymail\Beautymail;

class SendLinkToEditor implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * @var array
     */
    protected $data;

    /**
     * SendLinkToEditor constructor.
     *
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * @param Beautymail $mailer
     */
    public function handle(Beautymail $mailer)
    {
        $mailer->send('mail.editor-link', ['data' => $this->data], function ($message) {
            $message
                ->bcc(config('app.emails.support'))
                ->from(config('app.emails.robot'))
                ->to($this->data['email'])
                ->subject('Keep editing your logo!');
        });
    }

    /**
     * @return array
     */
    public function tags()
    {
        return ['send_link_to_editor', 'email:'.$this->data['email']];
    }
}
