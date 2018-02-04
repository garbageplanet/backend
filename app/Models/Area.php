<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Area extends Model
{
    protected $fillable = [
          'created_by'
        , 'title'
        , 'max_players'
        , 'contact'
        , 'note'
        , 'latlngs'
        , 'game'
    ];

    protected $hidden = ['secret'];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function tags()
    {
        return $this->hasMany('App\Models\Tag', 'trash_id');
    }

    public function creator()
    {
        return $this->belongsTo('App\Models\User', 'created_by');
    }

    public function player()
    {
        return $this->hasMany('App\Models\Player', 'curr_player');
    }

    public function trashesInsideArea($area)
    {
        $areageom = $area->geom;

        $query = "SELECT trashes.*
                  FROM trashes, areas
                  WHERE ST_Contains(areas.geom, trashes.geom)";

        $trashes = DB::select($query);

        // Get ids of the trashes
        $trash_ids = [];

        foreach ($trashes as $trash) {
            $trash_ids[] = $trash->id;
        }

        $trashes = Trash::whereIn('id', $trash_ids)->get();

        return $trashes;
    }

    public function makeArea()
    {
        $find_str = array(", ","[","]");
        $repl_str = array(" ","","");

        // Parse the latlngs array and close the polygon with the first latlng to create the geom
        // FIXME this works but ST_MakePolygon can make the polygon from a line automatically

        $geomlatlngs = str_replace($find_str, $repl_str, $this->latlngs);
        $geomlatlngs = explode(",",$geomlatlngs);
        $geomlatlngs = array_merge($geomlatlngs, array_slice($geomlatlngs,0, 1));
        $geomlatlngs = implode(",",$geomlatlngs);

        $query = "UPDATE areas SET geom = ST_SetSRID(ST_MakePolygon(ST_GeomFromText('LINESTRING($geomlatlngs)')), 4326) WHERE id = $this->id";

        $affected = DB::update($query);

        return $affected;
    }

}
