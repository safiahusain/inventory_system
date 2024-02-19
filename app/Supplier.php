<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $fillable =[

        "name", "image", "company_name", "vat_number",
        "email", "phone_number", "address", "city",
        "state", "postal_code", "country", "is_active",
        "res_phone", "office_phone",
        "driver","data","code","advance"

    ];

    public function product()
    {
    	return $this->hasMany('App/Product');

    }

    public function milk_spoilation()
    {
    	return $this->hasMany('App/MilkSpoilation');

    }
}
