<?php

namespace App\Models;

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
        return $this->belongsTo('App\Models\Trash');
    }

}

/*class LitterType extends Model
{
    protected $fillable = [
        'user_id',
        'type'
    ];

    public function litter()
    {
        return $this->belongsTo('App\Litter');
    }
}*/
