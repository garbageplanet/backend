<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

// TODO TrashType should be available to Litter

class TrashType extends Model
{
    protected $fillable = [
        'user_id', 
        'type'
    ];

    public function trash()
    {
        return $this->belongsTo('App\Trash');
    }
    
    public function litter()
    {
        return $this->belongsTo('App\Litter');
    }
}
