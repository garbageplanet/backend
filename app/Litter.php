<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Litter extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     
     TODO get lat lng as a single field
     TODO make a 'cleaned' - when a marker is marked as cleaned - Model 
      
     */
    protected $fillable = [
        'marked_by',
        'latlngs',
        'amount',
        'todo',
        'cleaned',
        'image_url',
        'note',
        'geojson_data',
        'wms_urls',
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

    public function types()
    {
        return $this->hasMany('App\TrashType', 'litter_id');
    }
    
    public function tags()
    {
        return $this->hasMany('App\Tag', 'litter_id');
    }
    
        public function confirms()
    {
        return $this->hasMany('App\Confirm', 'litter_id');
    }

    public function cleans()
    {
        return $this->hasMany('App\Clean', 'litter_id');
    }

    // TODO creator() vs user() for ownership?, aren't they the same
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
     * TODO make the polyline here
     */
    public function makePoint()
    {
        $affected = DB::update('UPDATE trashes SET geom = ST_SetSRID(ST_MakePoint(?, ?), 4326) WHERE id = ?', [$this->lat, $this->lng, $this->id]);
        return $affected;
    }

    public function addTypes($types)
    {
        $types = explode(",", $types);
        foreach ($types as $type) {
            $this->types()->create(['type' => $type]);
        }
        return true;
    }
    
}
