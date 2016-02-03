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

    public function meeting()
    {
        return $this->belongsToMany('App\Cleaning');
    }
    
        public function litter()
    {
        return $this->belongsToMany('App\Litter');
    }
    
        public function area()
    {
        return $this->belongsToMany('App\Area');
    }


    /********************
     * Relationships ends
     */
}
