<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Trash extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'marked_at',
        'marked_by',
        'lat',
        'lng',
        'amount',
        'status',
        'cleaned_at'
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

    public function tags()
    {
        return $this->belongsToMany('App\Tag');
    }

    public function cleans()
    {
        return $this->hasMany('App\Clean', 'trash_id');
    }

    public function markedBy()
    {
        return $this->belongsTo('App\User', 'marked_by');
    }

    /********************
     * Relationships ends
     */
}
