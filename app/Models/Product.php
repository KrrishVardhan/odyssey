<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'team_id', 'name', 'slug', 'description', 'about',
        'icon_path', 'banner', 'is_public',
    ];

    protected $casts = ['is_public' => 'boolean'];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }
    public function submissions()
    {
        return $this->hasMany(Submission::class);
    }
    public function followers()
    {
        return $this->belongsToMany(User::class, 'product_followers')->withTimestamps();
    }
}
