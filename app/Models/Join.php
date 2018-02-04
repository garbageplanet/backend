<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Join extends Model
{
    /**
    * NB: A 'Join' is the event of confirming a user comes to a cleaning event.
    * TODO make this as a count function oeach time a user joins
    * TODO allow user to remove their joining confirmation
    */
    protected $fillable = [
        'cleaning_id',
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
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    public function cleaning()
    {
        return $this->belongsTo('App\Models\Cleaning', 'cleaning_id');
    }

    /********************
     * Relationships ends
     */
}
