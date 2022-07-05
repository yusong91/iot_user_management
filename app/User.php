<?php

namespace Vanguard;

use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Mail;
use Vanguard\Events\User\RequestedPasswordResetEmail;
use Vanguard\Presenters\Traits\Presentable;
use Vanguard\Presenters\UserPresenter;
use Vanguard\Services\Auth\TwoFactor\Authenticatable as TwoFactorAuthenticatable;
use Vanguard\Services\Auth\TwoFactor\Contracts\Authenticatable as TwoFactorAuthenticatableContract;
use Vanguard\Support\Authorization\AuthorizationUserTrait;
use Vanguard\Support\CanImpersonateUsers;
use Vanguard\Support\Enum\UserStatus;

class User extends Authenticatable implements TwoFactorAuthenticatableContract, MustVerifyEmail
{
    use TwoFactorAuthenticatable,
        CanResetPassword,
        Presentable,
        AuthorizationUserTrait,
        Notifiable,
        CanImpersonateUsers,
        HasApiTokens,
        HasFactory;

    protected $presenter = UserPresenter::class;

    protected $table = 'users';

    protected $dates = ['last_login', 'birthday'];

    protected $fillable = [
        'email', 'password', 'username', 'first_name', 'last_name', 'phone', 'avatar',
        'address', 'country_id', 'birthday', 'last_login', 'confirmation_token', 'status',
        'remember_token', 'role_id', 'email_verified_at', 'parent_id', 'family'
    ];

    protected $hidden = ['password', 'remember_token'];

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

    public function setBirthdayAttribute($value)
    {
        $this->attributes['birthday'] = trim($value) ?: null;
    }

    public function gravatar()
    {
        $hash = hash('md5', strtolower(trim($this->attributes['email'])));

        return sprintf("https://www.gravatar.com/avatar/%s?size=150", $hash);
    }

    public function isUnconfirmed()
    {
        return $this->status == UserStatus::UNCONFIRMED;
    }

    public function isActive()
    {
        return $this->status == UserStatus::ACTIVE;
    }

    public function isBanned()
    {
        return $this->status == UserStatus::BANNED;
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function sendPasswordResetNotification($token)
    {
        Mail::to($this)->send(new \Vanguard\Mail\ResetPassword($token));

        event(new RequestedPasswordResetEmail($this));
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function children_asignproject(){

        return $this->hasMany('Vanguard\Model\AsignProject','user_id');
    }
    
    //AD Asign project to Worker and Client
    public function children_asignfolder(){

        return $this->hasOne('Vanguard\Model\AsignFolder','user_id');
    }

    public function children_asigndevice(){

        return $this->hasMany('Vanguard\Model\AsignDevice','user_id');
    }

    public static function boot() {
    	 
        parent::boot();  
        static::deleting(function($suspicious) {
            $suspicious->children_asignproject()->delete();
        });
    }
    
}
