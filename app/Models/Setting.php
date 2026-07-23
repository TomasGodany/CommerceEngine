<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $guarded = [];

    public static function current(): Setting
    {
        return static::firstOrCreate([], [
            'company_name' => config('company.name'),
            'company_street' => config('company.street'),
            'company_city' => config('company.city'),
            'company_country' => config('company.country'),
            'ico' => config('company.ico'),
            'dic' => config('company.dic'),
            'ic_dph' => config('company.ic_dph'),
            'iban' => config('company.iban'),
            'bic' => config('company.bic'),
            'email' => config('company.email'),
            'phone' => config('company.phone'),
            'default_currency_code' => 'EUR',
        ]);
    }
}
