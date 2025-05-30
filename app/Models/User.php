<?php

namespace App\Models;

use App\Notifications\SendVerifyWithQueueNotification;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements MustVerifyEmail, JWTSubject
{
    use HasFactory;
    use Notifiable;
    use SoftDeletes;

    public const ROLE_USER  = 'user';
    public const ROLE_ADMIN = 'admin';

    protected $fillable = [
        'name',
        'surname',
        'patronymic',
        'age',
        'gender',
        'address',
        'email',
        'password',
        'avatar',
        'phone_number',
        'role'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];


    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    public function isAdmin()
    {
        return $this->role == self::ROLE_ADMIN;
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'user_id');
    }

    public const GENDER_MALE   = 1;
    public const GENDER_FEMALE = 2;

    public static function getGenders()
    {
        return [
            self::GENDER_MALE   => 'Мужской',
            self::GENDER_FEMALE => 'Женский'
        ];
    }

    public function getGenderTitleAttribute()
    {
        return self::getGenders()[$this->gender];
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'user_id');
    }

    public function cart()
    {
        return $this->hasOne(Cart::class, 'user_id');
    }



    // Rest omitted for brevity

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function sendEmailVerificationNotification()
    {
        $this->notify(new SendVerifyWithQueueNotification());
    }
}
