<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Area extends Model
{
    protected $fillable = [
        'created_by',
        'title',
        'max_players',
        'contact',
        'note',
        'latlngs',
        'feature_type',
        'geom'
    ];
    
    protected $hidden = ['secret'];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function trashesInsideArea()
    {
        // TODO make polygon selection here not rectangle bounds
        $trashes = DB::select('
            SELECT *
            FROM trashes
        
            WHERE trashes.geom && ST_MakeEnvelope(?, ?, ?, ?)'
            , 
            [$this->sw_lat, $this->sw_lng, $this->ne_lat, $this->ne_lng]
        );

        //get id's of the trashes
        $trash_ids = [];
        foreach ($trashes as $trash) {
            $trash_ids[] = $trash->id;
        }
        $trashes = Trash::whereIn('id', $trash_ids)->get();

        return $trashes;
    }
    
    public function makeArea()
    { 
        $findstr = array(", ","[","]")
        $replstr = array(" ","","")
        $geomlatlngs = str_replace($findstr, latlngs, $replstr)
        
        $affected = DB::update('UPDATE trashes SET geom = ST_SetSRID(ST_MakePolygon(ST_GeomFromText("LINESTRING(?))), 4326) WHERE id = ?', [$this->$geomlatlngs, $this->id]);
        return $affected;
    }
    
    public function tags()
    {
        return $this->hasMany('App\Tag', 'trash_id');
    }

    public function creator()
    {
        return $this->belongsTo('App\User', 'created_by');
    }
  
    public function player()
    {
        return $this->hasMany('App\Player', 'curr_player');
    }
}
