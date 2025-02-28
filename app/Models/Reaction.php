<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reaction extends Model
{
    use HasFactory;

    // Define the table name explicitly
    protected $table = 'reactions_';  // Change this to the correct table name

    // Define fillable fields
    protected $fillable = ['post_id', 'user_id', 'reaction'];

    // Relation with the Post model
    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    // Relation with the User model
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
