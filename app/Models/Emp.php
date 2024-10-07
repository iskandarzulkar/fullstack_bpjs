<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Emp extends Model
{
    use HasFactory;
    
    protected $table    = 'emp';
    protected $fillable = [
        'firstname',
        'lastname',
        'email',
        'address',
        'name',
    ];
}
