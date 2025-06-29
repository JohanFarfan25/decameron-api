<?php

namespace App\Models;

use App\Traits\GeneratesUuid;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use GeneratesUuid;

    protected $fillable = [
        'uuid',
        'hotel_id',
        'room_type',
        'accommodation',
        'quantity',
    ];

    public function hotel()
    {
        return $this->belongsTo(Hotel::class, 'hotel_id');
    }
}
