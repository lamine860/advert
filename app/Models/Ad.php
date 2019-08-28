<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ad extends Model
{
    protected $fillable = [
        'title',
        'texte',
        'category_id',
        'region_id',
        'user_id',
        'departement',
        'commune',
        'commune_name',
        'commune_postal',
        'pseudo',
        'email',
        'limit',
        'active',
    ];


     /**
     * Get the region that owns the ad.
     */
    public function region()
    {
        return $this->belongsTo(Region::class);
    }
    /**
     * Get the category that owns the ad.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    /**
     * Get the photos for the ad.
     */
    public function photos()
    {
        return $this->hasMany(Upload::class);
    }
}
