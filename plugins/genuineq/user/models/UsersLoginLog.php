<?php namespace Genuineq\User\Models;

use Model;

class UsersLoginLog extends Model
{
    /**
     * @var string The database table used by the model.
     */
    protected $table = 'users_login_log';

    /**
     * @var array The attributes that are mass assignable.
     */
    protected $fillable = [
        'type',
        'name',
        'email',
        'ip_address'
    ];
}
