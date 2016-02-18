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

class Player extends Model
{
    /**
    NB: A 'Confirm' is the event of confirming there is garbage at a location.
    * TODO make this as a count function on each confirm
     */
    protected $fillable = [
        'area_id',
        'user_id',
        'glome_id',
        'curr_players'
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

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
    
    public function area()
    {
        return $this->belongsTo('App\Area', 'area_id');
    }



    /********************
     * Relationships ends
     */
}
