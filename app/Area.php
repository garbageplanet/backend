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
        'geom'
    ];
    
    protected $hidden = ['secret'];

    public function user()
    {
        return $this->belongsTo('App\User');
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

/*    public function trashesInsideArea()
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
    }*/
    
    public function makeArea()
    { 
        $find_str = array(", ","[","]");
        $repl_str = array(" ","","");
        
        // Parse the latlngs array and close the polygon with the first latlng to create the geom
        $geomlatlngs = str_replace($find_str, $repl_str, $this->latlngs);
        $geomlatlngs = explode(",",$geomlatlngs);
        $geomlatlngs = array_merge($geomlatlngs, array_slice($geomlatlngs,0, 1));
        $geomlatlngs = implode(",",$geomlatlngs);
        // dd($geomlatlngs);
        
        // "ST_AddPoint(the_geom, ST_PointN(the_geom, 1))"
        $query = "UPDATE areas SET geom = ST_SetSRID(ST_MakePolygon(ST_GeomFromText('LINESTRING($geomlatlngs)')), 4326) WHERE id = $this->id";

        $affected = DB::update($query);
        
        return $affected;
    }

}
