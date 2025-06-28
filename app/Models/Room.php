<?php

namespace App\Models;

use App\Traits\GeneratesUuid;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use GeneratesUuid;

    protected $fillable = [
        'uuid',
        'name',
        'status',
        'hotel_id',
        'room_type',
        'accommodation',
        'quantity',
    ];

    public function hotel()
    {
        return $this->belongsTo(hotels::class, 'hotel_id');
    }
}
