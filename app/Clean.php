<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Clean extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'trash_id',
        'user_id',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    /**********************
     * Relationships begins
     */

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function trash()
    {
        return $this->belongsTo('App\Trash', 'trash_id');
    }

    /********************
     * Relationships ends
     */
}
