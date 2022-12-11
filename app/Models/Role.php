<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Rol extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    // public function Users()
    // {
    //     // relacion de uno a muchos
    //     return $this->hasMany(User::class, 'id', 'id_role');
    // }

    /**
     * Get all of the comments for the Role
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'id_role', 'id');
    }

}
