<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email', 'password', 'verification_token', 'verified'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'verification_token'
    ];

    protected $dates = [
        'delete_token_generated_at',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function generateVerificationToken()
    {
        $this->verification_token = Str::random(60);
        $this->verified = false;
    }

    public function verify()
    {
        $this->verification_token = null;
        $this->verified = true;
    }

    public function generateDeleteToken()
    {
        $this->delete_token = Str::random(60);
        $this->delete_token_generated_at = now();
    }

    public function tags()
    {
        return $this->hasMany(Tag::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

    public function attempts()
    {
        return $this->hasManyThrough(Attempt::class, Task::class);
    }
}
