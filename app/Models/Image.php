<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Image extends Model
{
    use HasFactory;

    protected $fillable = ['path' , 'observation_id'];
    public function observation(): BelongsTo

    {
        return $this->belongsTo(Observation::class, 'observation_id');
    }
}
