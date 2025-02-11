<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Organization extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'building_id'];

    public function building()
    {
        return $this->belongsTo(Building::class);
    }

    public function phones()
    {
        return $this->hasMany(Phone::class);
    }

    public function activities()
    {
        return $this->belongsToMany(Activities::class, 'activity_organization');
    }
}
