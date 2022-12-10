<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Rol extends Model
{
    use HasFactory;

    public function Users()
    {
        // relacion de uno a muchos
        return $this->hasMany(User::class);
    }
}
