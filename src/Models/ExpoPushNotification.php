<?php
namespace Trin4ik\LaravelExpoPush\Models;

use Illuminate\Database\Eloquent\Model;

class ExpoPushNotification extends Model {
    protected $table = 'expo_push_notifications';

    protected $fillable = [
        'item_type',
        'item_id',
        'payload',
        'response',
        'code',
        'status'
    ];

    protected $casts = [
        'payload' => 'json',
        'response' => 'json'
    ];

    public function item()
    {
        return $this->morphTo();
    }

    public function getTable () {
        return $this->table;
    }
}
