<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Confirm extends Model
{
    /**
    NB: A 'Clean' is the event of cleaning garbage, not an actual cleaning event where people gather to clean garbage. A cleaning/gathering event is referred to as a 'Cleaning'.
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
