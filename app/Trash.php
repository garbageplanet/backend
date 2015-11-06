<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

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
        'cleaned_at',
        'geom'
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

    public function creator()
    {
        return $this->belongsTo('App\User', 'marked_by');
    }

    /********************
     * Relationships ends
     */
    
    /**
     * make point with lat and long values
     * @return Illuminate\Database\Eloquent\Model
     */
    public function makePoint()
    {
        $affected = DB::update('UPDATE trashes SET geom = ST_SetSRID(ST_MakePoint(?, ?), 4326) WHERE id = ?', [$this->lat, $this->lng, $this->id]);
        return $affected;
    }
}
