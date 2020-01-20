<?php

namespace App\Gateways;

use Illuminate\Support\Facades\Http;

class TelegramGateway
{
    protected static ?TelegramGateway $instance = null;

    public static function instance(): ?TelegramGateway
    {
        if (! self::$instance) {
            self::$instance = new TelegramGateway();
        }

        return self::$instance;
    }

    /** @var array Params payload. */
    protected array $payload = [];

    /** @var array Inline Keyboard Buttons. */
    protected array $buttons = [];

    /** @var string Bot Token. */
    protected string $token;


    public function __construct()
    {
        $this->payload['parse_mode'] = 'Markdown';
        $this->token = config('services.telegram.token');
    }

    /**
     * Notification message (Supports Markdown).
     *
     * @return $this
     */
    public function content(string $content, int $limit = null): self
    {
        $this->payload['text'] = $content;

        return $this;
    }


    /**
     * Recipient's Chat ID.
     *
     * @param int|string $chatId
     *
     * @return $this
     */
    public function to(int|string $chatId): self
    {
        $this->payload['chat_id'] = $chatId;

        return $this;
    }

    /**
     * Add an inline button.
     *
     * @return $this
     */
    public function button(string $text, string $url, int $columns = 2): self
    {
        $this->buttons[] = compact('text', 'url');

        $this->payload['reply_markup'] = json_encode([
            'inline_keyboard' => array_chunk($this->buttons, $columns),
        ]);

        return $this;
    }

    /**
     * add inline buttons
     *
     * @return $this
     */
    public function buttons(array $buttons)
    {
        foreach ($buttons as $button) {
            $this->button($button['text'], $button['url']);
        }

        return $this;
    }

    /**
     * Send the message silently.
     * Users will receive a notification with no sound.
     *
     * @return $this
     */
    public function disableNotification(bool $disableNotification = true): self
    {
        $this->payload['disable_notification'] = $disableNotification;

        return $this;
    }

    /**
     * set parse mode : 'Markdown' or 'HTML'
     *
     * @return $this
     */
    public function setParseMode(string $parse_mode = 'Markdown'): self
    {
        $this->payload['parse_mode'] = $parse_mode;

        return $this;
    }

    public function sendMessage()
    {
        return $this->sendRequest('sendMessage');
    }

    public function getUpdates()
    {
        return $this->sendRequest('getUpdates');
    }

    /**
     * Send an API request and return response.
     *
     */
    protected function sendRequest(string $endpoint)
    {
        $url = 'https://api.telegram.org/bot'.$this->token.'/'.$endpoint;

        return Http::post($url, $this->payload)->json();
    }
}
