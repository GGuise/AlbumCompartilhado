<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $guarded = [];

    public function getRouteKeyName(){
        return "slug";
    }

    public function price(){
       return 'R$ ' . number_format((float)$this->price, 2, ',', '.');
    }
}
