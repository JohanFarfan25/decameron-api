<?php

namespace App\Models;

use App\Traits\GeneratesUuid;
use Illuminate\Database\Eloquent\Model;

class Hotel extends Model
{
    use GeneratesUuid;

    protected $fillable = [
        'uuid',
        'name',
        'address',
        'city',
        'nit',
        'number_of_rooms',
    ];

    public function rooms()
    {
        return $this->hasMany(Room::class, 'hotel_id');
    }

}
