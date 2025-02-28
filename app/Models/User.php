<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'nom', 'prenom', 'pseudo', 'email', 'bio', 'password',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }


    public function sentRequests()
    {
        return $this->belongsToMany(User::class, 'friends', 'user_id', 'friend_id')
                    ->withPivot('status')
                    ->wherePivot('status', 'pending');
    }

    public function receivedRequests()
    {
        return $this->belongsToMany(User::class, 'friends', 'friend_id', 'user_id')
                    ->withPivot('status')
                    ->wherePivot('status', 'pending');
    }

    public function friendships()
    {
        return $this->hasMany(Friend::class);
    }


    public function friendOf()
    {
        return $this->hasMany(Friend::class, 'friend_id');
    }
    /**
 * Define a relationship for accepted friends
 */
public function friends()
{
    return $this->belongsToMany(User::class, 'friends', 'user_id', 'friend_id')
                ->withPivot('status')
                ->wherePivot('status', 'accepted');
}

public function posts()
{
    return $this->hasMany(Post::class);
}

}
