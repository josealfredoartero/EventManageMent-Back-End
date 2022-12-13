<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// use App\Models\Image;

class Publication extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'description',
        'id_user',
    ];

    /**
     * Get all of the comments for the Publication
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function images(){
        return $this->hasMany(Image::class, 'id');
    }
}
