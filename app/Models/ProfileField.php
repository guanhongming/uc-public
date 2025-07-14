<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProfileField extends Model
{
    protected $fillable = ['label', 'field_type', 'is_required', 'options'];

    public function answers()
    {
        return $this->hasMany(ProfileAnswer::class);
    }
}
