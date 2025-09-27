<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EsimProfile extends Model
{
    protected $fillable = ['subscriber_id', 'operator', 'status', 'activation_code'];

    public function subscriber()
    {
        return $this->belongsTo(Subscriber::class);
    }
}
