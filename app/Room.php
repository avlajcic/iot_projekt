<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $fillable = [
        'id','name','start_time','end_time','automatic','brightness','arduino_id','user_id'
    ];

    public $timestamps = false;


    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
