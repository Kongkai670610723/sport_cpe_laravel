<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'user_id';
    public $timestamps = false;

    protected $fillable = [
        'name',
        'email',
        'password_hash',
        'phone',
        'address',
        'role',
    ];

    protected $hidden = [
        'password_hash',
    ];

    public function getAuthPassword()
    {
        return $this->password_hash;
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'user_id', 'user_id');
    }
}
