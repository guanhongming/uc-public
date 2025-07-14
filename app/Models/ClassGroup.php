<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Model;

class ClassGroup extends Model
{
    public function users(): BelongsToMany
    {

        return $this->belongsToMany(User::class);
    }
}
