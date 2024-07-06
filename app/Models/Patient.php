<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        'username',
        'phone_number',
        'identity_card_number',
        'gender',
        'date_of_birth',
        'email',
        'password',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
