<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable {

    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];
    
    public $timestamps = false;

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'remember_token','password'
    ];

    public function is_admin() {
        if ($this->type == 1) {
            return true;
        } else {
            return false;
        }
    }
    
    public function is_manager() {
        if ($this->type == 2) {
            return true;
        } else {
            return false;
        }
    }
    public function is_user() {
        if ($this->type == 3) {
            return true;
        } else {
            return false;
        }
    }

}
