<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TariffUsage extends Model
{
    protected $fillable = ['subscriber_id', 'tariff_name', 'data_used', 'minutes_used', 'status'];

    public function subscriber()
    {
        return $this->belongsTo(Subscriber::class);
    }
}
