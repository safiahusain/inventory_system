<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MilkSpoilation extends Model
{
    protected $fillable =[

        "invoice",
        "user_id",
        "supplier_id",
        "description",
        "total_qty",
        "total_amount",
        "date",
        "data",
    ];

    public function supplier()
    {
    	return $this->belongsTo('App\Supplier');
    }
}
