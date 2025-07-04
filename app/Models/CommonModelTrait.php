<?php

namespace App\Models;

use Carbon\Carbon;

trait CommonModelTrait
{
    public $dateTimeFormat = "d/m/Y h:i A";

    public function getCreatedAtAttribute($value)
    {
        if (!empty($value)) {
            return Carbon::parse($value)->tz(config('app.timezone'))->format($this->dateTimeFormat);
        }
        return null;
    }
    public function getUpdatedAtAttribute($value)
    {
        if (!empty($value)) {
            return Carbon::now()->parse($value)->tz(config('app.timezone'))->format($this->dateTimeFormat);
        }
        return null;
    }
}
