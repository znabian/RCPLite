<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    protected $table="UserTbl";
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'Name',
        'Phone',
        'Verify',
        "Active",
        "Cancel"
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'Verify',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    public function getAuthPassword()
    {
        return $this->Verify;
    }
    public function MyUsers()
    {
        return $this->hasMany(SupportUser::class,'SupportId','Id');
    }
    public function MySupports()
    {
        return $this->hasMany(SupportUser::class,'UserId','Id');
    }
    public function Detail()
    {
        return $this->belongsTo(Detail::class,'Id','UserId');
    }
    public function Payments()
    {
        return $this->hasMany(Payment::class,'UserId','Id');
    }
    public function Tokens()
    {
        return $this->hasMany(Token::class,'UserId','Id');
    }
}
