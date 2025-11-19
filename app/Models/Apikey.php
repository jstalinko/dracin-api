<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Apikey extends Model
{
   
    protected $fillable = [
        'apikey',
        'start_at',
        'end_at',
        'latest_revoked',
        'active',
        'user_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
