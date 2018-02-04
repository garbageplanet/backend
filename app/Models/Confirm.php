<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Trash;
use App\Models\User;
use JWTAuth;
use DB;
use Carbon\Carbon;
use Auth;

class Confirm extends Model
{
    /**
    * NB A Confirm is the event of confirming there is garbage at a location.
    * TODO make this as a count function on each confirm
     */
    protected $fillable = [
          'trash_id'
        , 'user_id'
        , 'litter_id'
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

    public function trash()
    {
        return $this->belongsTo('App\Models\Trash', 'trash_id');
    }

    public function litter()
    {
        return $this->belongsTo('App\Models\Trash', 'litter_id');
    }

    /********************
     * Relationships ends
     */
}
