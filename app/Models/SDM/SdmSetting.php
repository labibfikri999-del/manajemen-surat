<?php

namespace App\Models\SDM;

use Illuminate\Database\Eloquent\Model;

class SdmSetting extends Model
{
    protected $guarded = ['id'];

    public static function getValue($key, $default = null)
    {
        $setting = self::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }
}
