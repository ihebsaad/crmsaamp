<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserLogin extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'app','login_at', 'logout_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
