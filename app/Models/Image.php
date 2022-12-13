<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// use App\Models\Publication;


class Image extends Model
{
    use HasFactory;

    protected $fillable = [
        'url',
        'id_publication'
    ];

    /**
     * Get the user that owns the Image
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function publication(){
        return $this->belongsTo(Publication::class, 'id_publication');
    }
}
