<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    public function ads()
    {
        return $this->hasMany(Ad::class);
    }
}
