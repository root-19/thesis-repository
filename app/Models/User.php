<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Notification;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'email_verification_code',
        'email_verification_code_expires_at',
        'profile_image_path',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'email_verification_code_expires_at' => 'datetime',
    ];

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isAuthor(): bool
    {
        return $this->role === 'author';
    }

    public function isUser(): bool
    {
        return $this->role === 'user';
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function unreadNotifications()
    {
        return $this->notifications()->whereNull('read_at');
    }

    public function getUnreadNotificationsCountAttribute()
    {
        return $this->unreadNotifications()->count();
    }

    public function savedTheses()
    {
        return $this->belongsToMany(Thesis::class, 'saved_theses', 'user_id', 'thesis_id')->withTimestamps();
    }
}
