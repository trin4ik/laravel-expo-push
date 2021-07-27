<?php

namespace Trin4ik\LaravelExpoPush;

class ExpoPush
{
    // android + ios
    protected array $data;
    protected string $title;
    protected string $body;
    protected int $ttl;
    protected int $expiration;
    protected string $priority;
    protected string $categoryId;

    // ios only
    protected string $subtitle;
    protected bool $sound;
    protected int $badge;
    protected bool $mutableContent;

    // android only
    protected string $channelId;

    // Build your next great package.
    public static function send (array $data, array $item) {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, config('expo-push.url_send'));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'accept: application/json',
            'content-type: application/json',
        ]);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        $response = [
            'body' => curl_exec($ch),
            'code' => curl_getinfo($ch, CURLINFO_HTTP_CODE)
        ];

        if (config('expo-push.log.enabled')) {
            (new (config('expo-push.log.driver.type')))->create([
                'payload' => $data,
                'response' => $response,
                'item' => $item
            ], config('expo-push.log.driver.instance') ?: null);
        }

        return true;
    }

    public static function create () {
        return new static();
    }

    public function data (array $data) {
        $this->data = $data;
        return $this;
    }
    public function title (string $title) {
        $this->title = $title;
        return $this;
    }
    public function body (string $body) {
        $this->body = $body;
        return $this;
    }
    public function ttl (int $ttl) {
        $this->ttl = $ttl;
        return $this;
    }
    public function expiration (int $expiration) {
        $this->expiration = $expiration;
        return $this;
    }
    public function priority (string $priority) {
        switch ($priority) {
            case 'normal':
            case 'high': {
                break;
            }
            default: {
                $this->priority = 'default';
            }
        }
        return $this;
    }
    public function subtitle (string $subtitle) {
        $this->subtitle = $subtitle;
        return $this;
    }
    public function sound (bool $sound) {
        $this->sound = $sound;
        return $this;
    }
    public function badge (int $badge) {
        $this->badge = $badge;
        return $this;
    }
    public function channelId (string $channelId) {
        $this->channelId = $channelId;
        return $this;
    }
    public function categoryId (string $categoryId) {
        $this->categoryId = $categoryId;
        return $this;
    }
    public function mutableContent (bool $mutableContent) {
        $this->mutableContent = $mutableContent;
        return $this;
    }
    public function toArray()
    {
        $result = [];

        if (!empty($this->data)) $result['data'] = $this->data;
        if (!empty($this->title)) $result['title'] = $this->title;
        if (!empty($this->body)) $result['body'] = $this->body;
        if (!empty($this->ttl)) $result['ttl'] = $this->ttl;
        if (!empty($this->expiration)) $result['expiration'] = $this->expiration;
        if (!empty($this->priority)) $result['priority'] = $this->priority;
        if (!empty($this->sound)) $result['sound'] = $this->sound ? 'default' : null;
        if (!empty($this->badge)) $result['badge'] = $this->badge;
        if (!empty($this->channelId)) $result['channelId'] = $this->channelId;
        if (!empty($this->categoryId)) $result['categoryId'] = $this->categoryId;
        if (!empty($this->mutableContent)) $result['mutableContent'] = $this->mutableContent;

        return $result;
    }
}
