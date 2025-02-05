<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Phones extends Model
{
    use HasFactory;

    protected $fillable = ['number', 'organization_id'];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }
}
