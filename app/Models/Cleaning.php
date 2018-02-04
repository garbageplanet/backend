<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class Cleaning extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
          //'created_by'
          'latlng'
        , 'note'
        , 'datetime'
        , 'recurrence'
        , 'joins'
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

    public function makePoint()
    {
        $query = "UPDATE cleanings SET geom = ST_SetSRID(ST_MakePoint($this->latlng), 4326) WHERE id = $this->id";

        $affected = DB::update($query);

        return $affected;
    }

    public function creator()
    {
        return $this->belongsTo('App\Models\User', 'created_by');
    }

    public function modifier()
    {
        return $this->belongsTo('App\Models\User', 'modified_by');
    }

    public function tags()
    {
        return $this->hasMany('App\Models\Tag');
    }

    public function attend()
    {

        $query = "UPDATE ONLY cleanings SET attends = attends + 1  WHERE id = $this->id";

        $affected = DB::update($query);

        return $affected;

    }

/*    public function users()
    {
        return $this->belongsToMany('App\User');
    }*/


    /********************
     * Relationships ends
     */
}
