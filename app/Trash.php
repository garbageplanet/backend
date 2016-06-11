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
     
     TODO get lat lng as a single field
      
     */
    protected $fillable = [
        'marked_by',
        'latlng',
        'amount',
        'todo',
        'image_url',
        'sizes',
        'embed',
        'note',
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
        return $this->hasMany('App\TrashType', 'trash_id');
    }
    
    public function tags()
    {
        return $this->hasMany('App\Tag', 'trash_id');
    }
    
    public function confirms()
    {
        return $this->hasMany('App\Confirm', 'trash_id');
    }

    public function cleans()
    {
        return $this->hasMany('App\Clean', 'trash_id');
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
     */
    public function makePoint()
    {
         
        $query = "UPDATE trashes SET geom = ST_SetSRID(ST_MakePoint($this->latlng), 4326) WHERE id = $this->id";
        
        $affected = DB::update($query);
        
        return $affected;
    }
    
    // confirm the presence of garbage at a marker
    public function confirm()
    {
         
        $query = "UPDATE ONLY trashes SET confirms = confirms + 1  WHERE id = $this->id";
        
        $affected = DB::update($query);
        
        return $affected;
    }
    
    public function cleaned()
    {
        // toggle the current value in the db
        $query = "UPDATE ONLY trashes SET cleaned = NOT cleaned WHERE id = $this->id";
        
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
