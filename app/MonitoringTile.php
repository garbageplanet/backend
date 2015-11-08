<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class MonitoringTile extends Model
{
    protected $fillable = [
        'name',
        'sw_lat',
        'sw_lng',
        'ne_lat',
        'ne_lng',
    ];

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function trashesInsideTile()
    {
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

}
