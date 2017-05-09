<?php

namespace App;

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
        return $this->belongsToMany('App\Trash');
    }

    public function cleanings()
    {
        return $this->belongsToMany('App\Cleaning');
    }
    
        public function litters()
    {
        return $this->belongsToMany('App\Litter');
    }
    
        public function areas()
    {
        return $this->belongsToMany('App\Area');
    }


    /********************
     * Relationships ends
     */
}
