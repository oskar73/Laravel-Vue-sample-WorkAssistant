<?php

namespace App\Integration;

use Illuminate\Support\Facades\Http;

class Mailcow
{
    const get_domains = "get/domain/all";
    const add_domain = "add/domain";
    const update_domain = "edit/domain";
    const delete_domain = "delete/domain";
    const add_mailbox = "add/mailbox";
    const edit_mailbox = "edit/mailbox";
    const delete_mailbox = "delete/mailbox";

    public $endpoint;
    public $http;
    public $url;
    public $params;

    public function __construct()
    {
        $this->endpoint = option("mailcow_server", '') . '/api/v1/';
        $apikey = option("mailcow_apikey", '');

        $this->http = Http::withHeaders([
            "Content-Type" => "application/json",
            "X-API-Key" => $apikey,
        ]);
    }
    public function setUrl($url)
    {
        $this->url = $this->endpoint . $url;

        return $this;
    }
    public function setParams($params)
    {
        $old = $this->params;
        foreach ($params as $key => $param) {
            $old[$key] = $param;
        }
        $this->params = $old;

        return $this;
    }
    public function dispatch($type = "get")
    {
        \Log::info($this->params);
        switch ($type) {
            case "post":
                $response = $this->http->post($this->url, $this->params);

                break;
            case "put":
                $response = $this->http->put($this->url, $this->params);

                break;
            default:
                $response = $this->http->get($this->url, $this->params);

        }
        \Log::info($response);
        if ($response->ok()) {
            return $response->json();
        } else {
            $response->throw();
        }
    }
    public function getDomains()
    {
        return $this->setUrl(self::get_domains)
            ->dispatch();
    }
    public function addDomain()
    {
        return $this->setUrl(self::add_domain)
            ->dispatch("post");
    }
    public function updateDomain()
    {
        return $this->setUrl(self::update_domain)
            ->dispatch("post");
    }
    public function deleteDomain()
    {
        return $this->setUrl(self::delete_domain)
            ->dispatch("post");
    }
    public function createMailbox()
    {
        return $this->setUrl(self::add_mailbox)
            ->dispatch("post");
    }
    public function updateMailbox()
    {
        return $this->setUrl(self::edit_mailbox)
            ->dispatch("post");
    }
    public function deleteMailbox()
    {
        return $this->setUrl(self::delete_mailbox)
            ->dispatch("post");
    }
}
