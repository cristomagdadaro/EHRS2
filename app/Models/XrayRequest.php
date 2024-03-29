<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class XrayRequest extends Model
{
    use HasFactory;

    protected $table = 'xray_requests';

    protected $fillable = [
        'id',
        'infirmary_id',
        'or_no',
        'rqst_for',
        'history',
        'rqst_physician',
        'ward',
        'status',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class, 'infirmary_id', 'infirmary_id');
    }

    public function xray()
    {
        return $this->belongsTo(Xray::class, 'id', 'rqst_id')->with('radiologist')->with('radiographer');
    }

    public function rqstPhysician()
    {
        return $this->belongsTo(User::class, 'rqst_physician', 'id');
    }
}
