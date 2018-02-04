<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    /**
      * NB: A 'Clean' is the event of cleaning garbage, not an actual cleaning event where people gather to clean garbage. A cleaning/gathering event is referred to as a 'Cleaning'.
      */
    protected $fillable = [
        'area_id',
        'user_id'
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



    /********************
     * Relationships ends
     */
}
