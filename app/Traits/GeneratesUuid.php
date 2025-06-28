<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait GeneratesUuid
{
    /**
     * Genera un UUID para el modelo.
     * @author Johan Alexander Farfán Sierra <johanfarfan25@gmail.com>
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->uuid = Str::uuid()->toString();
        });
    }
}