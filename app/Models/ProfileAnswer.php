<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProfileAnswer extends Model
{
    protected $fillable = ['user_id', 'profile_field_id', 'value'];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function profileField()
    {
        return $this->belongsTo(ProfileField::class);
    }
}
