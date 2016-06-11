<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Litter extends Model
{
    protected $fillable = [
        'marked_by',
        'latlngs',
        'amount',
        'todo',
        'image_url',
        'note',
        'physical_length',
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

    public function creator()
    {
        return $this->belongsTo('App\User', 'marked_by');
    }

    /********************
     * Relationships ends
     */

    /**
     * make point with latlng values
     * @return Illuminate\Database\Eloquent\Model
     */
    public function makeLine()
    {
        $find_str = array(", ","[","]");
        $repl_str = array(" ","","");
        $geomlatlngs = str_replace($find_str, $repl_str, $this->latlngs);
                
        $query = "UPDATE litters SET geom = ST_SetSRID(ST_GeomFromText('LINESTRING($geomlatlngs)'), 4326) WHERE id = $this->id";
        
        $affected = DB::update($query);
        
        return $affected;
    }

    // confirm the presence of garbage at a litter
    public function confirm()
    {
         
        $query = "UPDATE ONLY litters SET confirms = confirms + 1  WHERE id = $this->id";
        
        $affected = DB::update($query);
        
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
