<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MonitoringTile extends Model
{
    protected $fillable = [
        'name',
        'sw_lat',
        'sw_lng',
        'ne_lat',
        'ne_lng',
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

}
