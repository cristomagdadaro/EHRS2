<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Services extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'services';

    protected $fillable = [
        'id',
        'name',
        'description',
        'schedule',
        'section_name',
        'room_no',
    ];

    public function fees()
    {
        return $this->belongsTo(Fees::class);
    }
}
