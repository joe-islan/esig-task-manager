<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Task extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'responsible', 'priority', 'deadline', 'status'];

    public function setResponsibleAttribute($value)
    {
        $this->attributes['responsible'] = ucwords(strtolower($value));
    }
}
