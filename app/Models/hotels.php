<?php

namespace App\Models;

use App\Traits\GeneratesUuid;
use Illuminate\Database\Eloquent\Model;

class hotels extends Model
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
        return $this->hasMany(rooms::class, 'hotel_id');
    }

}
