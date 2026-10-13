<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Participant extends Model
{
    use HasFactory;

    protected $fillable = ['full_name', 'student_id', 'email', 'phone', 'course', 'year_level'];

    public function registrations(): HasMany
    {
        return $this->hasMany(Registration::class);
    }
}