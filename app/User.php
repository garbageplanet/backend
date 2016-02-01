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

    public function cleans()
    {
        return $this->hasMany('App\Clean', 'user_id');
    }

    public function createdMeetings()
    {
        return $this->hasMany('App\Clean', 'created_by');
    }

    public function modifiedMeetings()
    {
        return $this->hasMany('App\Clean', 'modified_by');
    }

    public function participations()
    {
        return $this->hasMany('App\Clean', 'user_id');
    }

    public function monitoringTiles()
    {
        return $this->hasMany('App\MonitoringTile');
    }

    /********************
     * Relationships ends
     */
}
