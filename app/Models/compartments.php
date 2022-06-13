<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class compartments extends Model
{
    use HasFactory;

    public function files()
    {
        return $this->belongsToMany(Files::class, 'compartment_file', 'compartment_id', 'file_id');
    }
}
