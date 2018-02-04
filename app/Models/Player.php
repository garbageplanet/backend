<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Trash;
use App\Models\Clean;
use App\Models\User;
use JWTAuth;
use DB;
use Carbon\Carbon;
use Auth;

class Player extends Model
{
    /**

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
        return $this->belongsTo('App\Models\User', 'user_id');
    }

    public function area()
    {
        return $this->belongsTo('App\Models\Area', 'area_id');
    }



    /********************
     * Relationships ends
     */
}
