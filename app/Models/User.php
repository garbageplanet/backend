<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

use Tymon\JWTAuth\Contracts\JWTSubject as AuthenticatableUserContract;

use SoftDeletes;

// class User extends \TCG\Voyager\Models\User implements AuthenticatableContract,
class User extends Model implements AuthenticatableContract,
                                    AuthorizableContract,
                                    CanResetPasswordContract,
                                    AuthenticatableUserContract

{
    use Authenticatable, Authorizable, CanResetPassword;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'email', 'password'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    protected $dates = ['deleted_at'];

    /**
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();  // Eloquent model method
    }

    /**
     * @return array
     */
    public function getJWTCustomClaims()
    {

      // $payload = JWTAuth::parseToken()->getPayload();
      //
      // dd($payload);
      //
      // $payload->get('foo');


        return [
             'user' => [
                'id' => $this->id,
                'iemail' => $this->iemail,
                'token' => $this->token,
             ]
        ];
    }

    /**********************
     * Relationships begins
     * TODO make a single model that encompasses the below
     */

    public function markedTrashes()
    {
        return $this->hasMany('App\Models\Trash', 'marked_by');
    }

    public function markedLitters()
    {
        return $this->hasMany('App\Models\Litter', 'marked_by');
    }

    public function cleanedTrashes()
    {
        return $this->hasMany('App\Models\Clean', 'user_id');
    }

    public function createdCleanings()
    {
        return $this->hasMany('App\Models\Cleaning', 'created_by');
    }

    public function createdAreas()
    {
        return $this->hasMany('App\Models\Area', 'created_by');
    }

/*    public function modifiedCleanings()
    {
        return $this->hasMany('App\Models\Cleaning', 'modified_by');
    }

    public function joinedCleaning()
    {
        return $this->hasMany('App\Models\Cleaning', 'user_id');
    }*/


    /********************
     * Relationships ends
     */
}
