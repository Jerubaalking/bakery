<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    protected $fillable = ['name', 'active'];

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
