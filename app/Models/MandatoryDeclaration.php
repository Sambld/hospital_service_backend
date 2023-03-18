<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class MandatoryDeclaration extends Model
{
    use HasFactory;

    protected $table = 'mandatory_declaration';
    protected $fillable = [
      'diagnosis', 'detail','medical_record_id'
    ];
}
