<?php namespace Genuineq\User\Models;

use Model;

class UsersLoginLog extends Model
{
    /**
     * @var string The database table used by the model.
     */
    protected $table = 'users_login_log';

    /**
     * @var string The primary key for the model.
     */
    protected $primaryKey = 'id';
}
