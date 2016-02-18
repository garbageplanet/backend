<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Trash;
use App\Clean;
use App\User;
use JWTAuth;
use DB;
use Carbon\Carbon;
use Auth;

class Cleaning extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'created_by',
        'modified_by',
        'lat',
        'lng',
        'place',
        'name',
        'note',
        'organizer',
        'begins_at',
        'featuretype',
        'tag'
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
        $affected = DB::update('UPDATE cleanings SET geom = ST_SetSRID(ST_MakePoint(?, ?), 4326) WHERE id = ?', [$this->lat, $this->lng, $this->id]);
        return $affected;
    }
  
    public function creator()
    {
        return $this->belongsTo('App\User', 'created_by');
    }

    public function modifier()
    {
        return $this->belongsTo('App\User', 'modified_by');
    }
  
    public function tags()
    {
        return $this->hasMany('App\Tag');
    }

    public function users()
    {
        return $this->belongsToMany('App\User');
    }


    /********************
     * Relationships ends
     */
}
