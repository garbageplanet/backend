<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends Model implements AuthenticatableContract,
                                    AuthorizableContract,
                                    CanResetPasswordContract
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

    /**********************
     * Relationships begins
     */

    public function markedTrashes()
    {
        return $this->hasMany('App\Trash', 'marked_by');
    }
                                        
    public function markedLitters()
    {
        return $this->hasMany('App\Litter', 'marked_by');
    }

    public function cleanedTrashes()
    {
        return $this->hasMany('App\Clean', 'user_id');
    }

    public function createdCleanings()
    {
        return $this->hasMany('App\Cleaning', 'created_by');
    }
                                        
    public function createdAreas()
    {
        return $this->hasMany('App\Area', 'created_by');
    }

    public function modifiedCleanings()
    {
        return $this->hasMany('App\Cleaning', 'modified_by');
    }
                                      
    public function joinedCleaning()
    {
        return $this->hasMany('App\Cleaning', 'user_id');
    }


    /********************
     * Relationships ends
     */
}
