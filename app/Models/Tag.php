<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'tag',
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

    public function trashes()
    {
        return $this->belongsToMany('App\Models\Trash');
    }

    public function cleanings()
    {
        return $this->belongsToMany('App\Models\Cleaning');
    }

        public function litters()
    {
        return $this->belongsToMany('App\Models\Litter');
    }

        public function areas()
    {
        return $this->belongsToMany('App\Models\Area');
    }


    /********************
     * Relationships ends
     */
}
