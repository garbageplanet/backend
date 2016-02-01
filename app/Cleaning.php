<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Cleaning extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     
     TODO get lat lng as a single field
      
     */
    protected $fillable = [
        'marked_by',
        'date'
        'recurrence',
        'lat',
        'lng',
        'tag',
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
