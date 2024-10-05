<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable =[
        'firstname',
        'lastname',
        'email',
        'address'        
    ];


    /**
     * Get the user that owns the employee
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Get the user that owns the employee
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    // public function department(): hasOne
    // {
    //     return $this->hasOne(Department::class);
    // }
}
