<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Orientation extends Model
{
    public function cascades() {
        return $this->hasMany('App\Cascade');
    }
}
