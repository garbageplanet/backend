<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Area extends Model
{
    protected $fillable = [
        'name',
        'players',
        'contact',
        'note',
        'latlngs'
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
    
    public function tags()
    {
        return $this->hasMany('App\Tag', 'trash_id');
    }

}
