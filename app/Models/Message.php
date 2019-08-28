<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = [
        'texte',
        'email',
        'ad_id',
    ];

    public function ad()
    {
        return $this->belongsTo(Ad::class);
    }

}
