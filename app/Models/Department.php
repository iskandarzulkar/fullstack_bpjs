<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Department extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name'
    ];

  
    /**
     * Get the user associated with the department
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function employee(): HasOne
    {
        return $this->hasOne(employee::class);
    }
  
    /**
     * Get all of the comments for the department
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    // public function employee()
    // {
    //     return $this->belongsTo(Employee::class);
    // }
    /**
     * Get the user associated with the department
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    // public function employee(): HasOne
    // {
    //     return $this->hasOne(Employee::class, 'employees');
    // }
}
