<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscriber extends Model
{
    protected $fillable = ['user_id', 'current_operator'];

    public function esimProfiles()
    {
        return $this->hasMany(EsimProfile::class);
    }

    public function tariffs()
    {
        return $this->hasMany(TariffUsage::class);
    }
}
