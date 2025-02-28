<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FriendRequest extends Model
{
    protected $table = 'friends'; 
    protected $fillable = ['user_id', 'friend_id', 'status'];

    const PENDING = 'pending';
    const ACCEPTED = 'accepted';
    const REJECTED = 'rejected';
    public function accept()
    {
        $this->update(['status' => self::ACCEPTED]);
    }
    public function reject()
    {
        $this->update(['status' => self::REJECTED]);
    }
    public function isPending()
    {
        return $this->status === self::PENDING;
    }

    public function isAccepted()
    {
        return $this->status === self::ACCEPTED;
    }

    public function isRejected()
    {
        return $this->status === self::REJECTED;
    }
}
