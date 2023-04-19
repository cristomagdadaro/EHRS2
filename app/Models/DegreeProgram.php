<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DegreeProgram extends Model
{
    use HasFactory;

    protected $table = 'degree_programs';
    protected $fillable = [
        'name',
        'abbr',
        'major',
        'group',
        'department_id',
        'is_active',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function clients()
    {
        return $this->hasMany(Client::class);
    }
}
