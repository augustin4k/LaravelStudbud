<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class files extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'file_path',
    ];

    public function compartments()
    {
        // return $this->belongsToMany(Compartments::class, 'compartment_file', 'compartment_id', 'file_id');
    }
}
