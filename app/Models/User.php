<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Carbon\Carbon;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    public $timestamps = true;

    /**
     * Cho phép mass-assignment (updateOrCreate trong seeder, register, ...).
     * Password được cast sang hash tự động khi gán plaintext.
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'full_name',
        'nickname',
        'email',
        'password',
        'avatar',
        'bio',
        'tick',
        'followings_count',
        'likes_count',
        'followed',
    ];

    /**
     * Ẩn password khi serialize (response JSON, session, ...).
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'tick' => 'boolean',
            'followed' => 'boolean',
            'email_verified_at' => 'datetime',
        ];
    }

    public function getCreatedAtAttribute($date)
    {
        return Carbon::parse($date)->format('Y-m-d H:i:s');
    }

    public function getUpdatedAtAttribute($date)
    {
        return Carbon::parse($date)->format('Y-m-d H:i:s');
    }
}
