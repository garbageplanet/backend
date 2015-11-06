<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TrashType extends Model
{
    protected $fillable = ['user_id', 'type'];

    public function trash()
    {
        return $this->belongsTo('App\Trash');
    }
}
